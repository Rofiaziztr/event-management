<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better query performance

        // Events table indexes
        Schema::table('events', function (Blueprint $table) {
            // Index for status filtering (common query)
            $table->index('status');

            // Index for date range queries
            $table->index('start_time');
            $table->index('end_time');

            // Composite index for status and date filtering
            $table->index(['status', 'start_time']);
            $table->index(['status', 'end_time']);

            // Index for creator queries
            $table->index('creator_id');
        });

        // Users table additional indexes
        Schema::table('users', function (Blueprint $table) {
            // Index for role filtering
            $table->index('role');

            // Index for division queries
            $table->index('division');

            // Composite index for role and division
            $table->index(['role', 'division']);
        });

        // Event participants table indexes
        Schema::table('event_participants', function (Blueprint $table) {
            // Index for event queries
            $table->index('event_id');

            // Index for user queries
            $table->index('user_id');

            // Composite index for participant lookups
            $table->index(['event_id', 'user_id']);
        });

        // Attendances table additional indexes
        Schema::table('attendances', function (Blueprint $table) {
            // Index for check-in time queries
            $table->index('check_in_time');

            // Composite index for event attendance queries
            $table->index(['event_id', 'check_in_time']);
        });

        // Documents table indexes
        Schema::table('documents', function (Blueprint $table) {
            // Index for event document queries
            $table->index('event_id');

            // Index for uploader queries
            $table->index('uploader_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_time']);
            $table->dropIndex(['end_time']);
            $table->dropIndex(['status', 'start_time']);
            $table->dropIndex(['status', 'end_time']);
            $table->dropIndex(['creator_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['division']);
            $table->dropIndex(['role', 'division']);
        });

        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['event_id', 'user_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['check_in_time']);
            $table->dropIndex(['event_id', 'check_in_time']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
            $table->dropIndex(['uploader_id']);
        });
    }
};
