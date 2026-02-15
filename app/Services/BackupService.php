<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class BackupService
{
    private const DAILY_RETENTION = 7;
    private const WEEKLY_RETENTION = 4;
    private const MONTHLY_RETENTION = 12;

    /**
     * Create database backup
     */
    public function createBackup(): string
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path("app/backups/$filename");

        // Ensure backup directory exists
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s 2>&1',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Backup command failed: ' . implode("\n", $output));
        }

        Log::info('Database backup created', [
            'filename' => $filename,
            'size' => filesize($path),
        ]);

        return $filename;
    }

    /**
     * Verify backup integrity
     */
    public function verifyBackup(string $filename): bool
    {
        $path = storage_path("app/backups/$filename");

        if (!file_exists($path)) {
            return false;
        }

        $size = filesize($path);
        
        // Backup should be at least 1KB
        if ($size < 1024) {
            Log::warning('Backup file too small', [
                'filename' => $filename,
                'size' => $size,
            ]);
            return false;
        }

        // Check if file contains SQL
        $content = file_get_contents($path, false, null, 0, 1000);
        if (strpos($content, 'MySQL dump') === false && strpos($content, 'CREATE TABLE') === false) {
            Log::warning('Backup file does not appear to be valid SQL', [
                'filename' => $filename,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Clean old backups according to retention policy
     */
    public function cleanOldBackups(): void
    {
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            return;
        }

        $files = glob($backupPath . '/backup_*.sql');
        $now = time();

        foreach ($files as $file) {
            $fileTime = filemtime($file);
            $age = ($now - $fileTime) / 86400; // Age in days

            $shouldDelete = false;

            // Daily backups: keep for 7 days
            if ($age > self::DAILY_RETENTION && $age <= 30) {
                // Keep weekly backups (every 7 days)
                $dayOfWeek = date('w', $fileTime);
                if ($dayOfWeek != 0) { // Not Sunday
                    $shouldDelete = true;
                }
            }

            // Weekly backups: keep for 4 weeks (28 days)
            if ($age > 28 && $age <= 365) {
                // Keep monthly backups (first of month)
                $dayOfMonth = date('j', $fileTime);
                if ($dayOfMonth != 1) { // Not first of month
                    $shouldDelete = true;
                }
            }

            // Monthly backups: keep for 12 months
            if ($age > 365) {
                $shouldDelete = true;
            }

            if ($shouldDelete) {
                unlink($file);
                Log::info('Old backup deleted', [
                    'file' => basename($file),
                    'age_days' => round($age, 1),
                ]);
            }
        }
    }

    /**
     * Notify administrators of backup status
     */
    public function notifyAdmins(Exception $exception): void
    {
        $adminEmail = config('mail.admin_email', 'admin@mamtours.com');

        try {
            Mail::raw(
                "Database backup failed:\n\n" . $exception->getMessage() . "\n\n" . $exception->getTraceAsString(),
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                        ->subject('[MAM Tours] Database Backup Failed');
                }
            );
        } catch (Exception $e) {
            Log::error('Failed to send backup failure notification', [
                'error' => $e->getMessage(),
            ]);
        }

        // Also send to Sentry
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }

    /**
     * Get backup statistics
     */
    public function getStatistics(): array
    {
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            return [
                'total_backups' => 0,
                'total_size' => 0,
                'oldest_backup' => null,
                'newest_backup' => null,
            ];
        }

        $files = glob($backupPath . '/backup_*.sql');
        $totalSize = 0;
        $oldest = null;
        $newest = null;

        foreach ($files as $file) {
            $totalSize += filesize($file);
            $time = filemtime($file);
            
            if ($oldest === null || $time < $oldest) {
                $oldest = $time;
            }
            
            if ($newest === null || $time > $newest) {
                $newest = $time;
            }
        }

        return [
            'total_backups' => count($files),
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'oldest_backup' => $oldest ? date('Y-m-d H:i:s', $oldest) : null,
            'newest_backup' => $newest ? date('Y-m-d H:i:s', $newest) : null,
        ];
    }
}
