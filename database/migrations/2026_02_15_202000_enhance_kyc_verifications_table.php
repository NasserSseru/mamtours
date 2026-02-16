<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            // Add new status tracking fields
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('verified_at');
            
            // Add verification/rejection by user tracking
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->onDelete('set null');
            $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('users')->onDelete('set null');
            
            // Add notes fields
            $table->text('verification_notes')->nullable()->after('verified_by');
            $table->text('additional_info_message')->nullable()->after('rejection_reason');
            
            // Add automated checks
            $table->json('automated_checks')->nullable()->after('additional_info_message');
            $table->timestamp('automated_checks_at')->nullable()->after('automated_checks');
            
            // Add document metadata
            $table->json('document_metadata')->nullable()->after('permit_original_document_path');
            
            // Add risk score
            $table->integer('risk_score')->nullable()->after('document_metadata');
            
            // Add indexes for performance
            $table->index('status');
            $table->index('submitted_at');
            $table->index('verified_at');
            $table->index(['user_id', 'status']);
        });

        // Create KYC audit logs table
        Schema::create('kyc_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyc_verification_id')->constrained()->onDelete('cascade');
            $table->string('action', 50);
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');
            
            $table->index(['kyc_verification_id', 'created_at']);
            $table->index('action');
        });

        // Create KYC document versions table (for resubmissions)
        Schema::create('kyc_document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyc_verification_id')->constrained()->onDelete('cascade');
            $table->integer('version');
            $table->string('document_type', 50);
            $table->string('document_path');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['kyc_verification_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_document_versions');
        Schema::dropIfExists('kyc_audit_logs');
        
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['verified_at']);
            $table->dropIndex(['submitted_at']);
            $table->dropIndex(['status']);
            
            $table->dropColumn([
                'submitted_at',
                'rejected_at',
                'verified_by',
                'rejected_by',
                'verification_notes',
                'additional_info_message',
                'automated_checks',
                'automated_checks_at',
                'document_metadata',
                'risk_score',
            ]);
        });
    }
};
