<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FIX: Add CASCADE DELETE constraint on event_id in event_calendar_syncs table
     * This ensures sync records are automatically deleted when events are deleted
     */
    public function up(): void
    {
        Schema::table('event_calendar_syncs', function (Blueprint $table) {
            // Drop existing foreign key constraint if it exists
            try {
                $table->dropForeign(['event_id']);
            } catch (\Exception $e) {
                // Constraint might not exist, that's fine
                \Illuminate\Support\Facades\Log::info('Constraint not found during migration, skipping drop', [
                    'error' => $e->getMessage()
                ]);
            }

            // Re-add with CASCADE DELETE
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_calendar_syncs', function (Blueprint $table) {
            try {
                $table->dropForeign(['event_id']);
            } catch (\Exception $e) {
                // Constraint might not exist, that's fine
                \Illuminate\Support\Facades\Log::info('Constraint not found during rollback, skipping drop', [
                    'error' => $e->getMessage()
                ]);
            }

            // Restore original constraint without CASCADE
            $table->foreign('event_id')->references('id')->on('events');
        });
    }
};
