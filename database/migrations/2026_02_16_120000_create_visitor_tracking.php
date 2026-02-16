<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorTracking extends Migration
{
    public function up()
    {
        // Track page visits
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('referrer')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('created_at');
            $table->index('user_id');
            $table->index('page_url');
        });

        // Track user sessions
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->unique();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, tablet, desktop
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->timestamp('last_activity_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('created_at');
        });

        // Track user actions
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type'); // view, click, submit, search, etc
            $table->string('action_name');
            $table->string('resource_type')->nullable(); // car, booking, review, etc
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
            $table->index('action_type');
            $table->index('created_at');
        });

        // Daily analytics summary
        Schema::create('daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('total_visitors');
            $table->integer('unique_visitors');
            $table->integer('total_page_views');
            $table->integer('new_users');
            $table->integer('total_bookings');
            $table->decimal('total_booking_value', 12, 2)->default(0);
            $table->integer('completed_bookings');
            $table->integer('pending_bookings');
            $table->decimal('avg_session_duration', 8, 2)->default(0);
            $table->decimal('bounce_rate', 5, 2)->default(0);
            $table->json('top_pages')->nullable();
            $table->json('traffic_sources')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_analytics');
        Schema::dropIfExists('user_actions');
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('page_visits');
    }
}
