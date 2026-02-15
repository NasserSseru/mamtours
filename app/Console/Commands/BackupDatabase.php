<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Exception;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--verify : Verify backup after creation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $this->info('Starting database backup...');

        try {
            $filename = $backupService->createBackup();
            $this->info("Backup created: $filename");

            if ($this->option('verify')) {
                $this->info('Verifying backup...');
                
                if ($backupService->verifyBackup($filename)) {
                    $this->info('✓ Backup verified successfully');
                } else {
                    $this->error('✗ Backup verification failed');
                    return 1;
                }
            }

            $this->info('Cleaning old backups...');
            $backupService->cleanOldBackups();

            $stats = $backupService->getStatistics();
            $this->info("Total backups: {$stats['total_backups']}");
            $this->info("Total size: {$stats['total_size_mb']} MB");

            return 0;
        } catch (Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            $backupService->notifyAdmins($e);
            return 1;
        }
    }
}
