<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add performance indexes for better query optimization

        // Events table - add missing indexes
        Schema::table('events', function (Blueprint $table) {
            // Index for date range queries (if not exists)
            if (!$this->indexExists('events', 'start_time')) {
                $table->index('start_time');
            }
            if (!$this->indexExists('events', 'end_time')) {
                $table->index('end_time');
            }

            // Composite index for status and date filtering
            if (!$this->indexExists('events', ['status', 'start_time'])) {
                $table->index(['status', 'start_time']);
            }
            if (!$this->indexExists('events', ['status', 'end_time'])) {
                $table->index(['status', 'end_time']);
            }
        });

        // Users table - add missing indexes
        Schema::table('users', function (Blueprint $table) {
            // Index for role filtering
            if (!$this->indexExists('users', 'role')) {
                $table->index('role');
            }

            // Index for division queries
            if (!$this->indexExists('users', 'division')) {
                $table->index('division');
            }

            // Composite index for role and division
            if (!$this->indexExists('users', ['role', 'division'])) {
                $table->index(['role', 'division']);
            }
        });

        // Event participants table - add missing indexes
        Schema::table('event_participants', function (Blueprint $table) {
            // Composite index for participant lookups
            if (!$this->indexExists('event_participants', ['event_id', 'user_id'])) {
                $table->index(['event_id', 'user_id']);
            }
        });

        // Attendances table - add missing indexes
        Schema::table('attendances', function (Blueprint $table) {
            // Index for check-in time queries
            if (!$this->indexExists('attendances', 'check_in_time')) {
                $table->index('check_in_time');
            }

            // Composite index for event attendance queries
            if (!$this->indexExists('attendances', ['event_id', 'check_in_time'])) {
                $table->index(['event_id', 'check_in_time']);
            }
        });

        // Documents table - add missing indexes
        Schema::table('documents', function (Blueprint $table) {
            // Index for uploader queries
            if (!$this->indexExists('documents', 'uploader_id')) {
                $table->index('uploader_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes if they exist
        $this->dropIndexIfExists('events', 'start_time');
        $this->dropIndexIfExists('events', 'end_time');
        $this->dropIndexIfExists('events', ['status', 'start_time']);
        $this->dropIndexIfExists('events', ['status', 'end_time']);

        $this->dropIndexIfExists('users', 'role');
        $this->dropIndexIfExists('users', 'division');
        $this->dropIndexIfExists('users', ['role', 'division']);

        $this->dropIndexIfExists('event_participants', ['event_id', 'user_id']);

        $this->dropIndexIfExists('attendances', 'check_in_time');
        $this->dropIndexIfExists('attendances', ['event_id', 'check_in_time']);

        $this->dropIndexIfExists('documents', 'uploader_id');
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $columns)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            $columnNames = is_array($columns) ? $columns : [$columns];

            foreach ($indexes as $index) {
                if (in_array($index->Column_name, $columnNames)) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist to be safe
            return false;
        }

        return false;
    }

    /**
     * Drop index if exists
     */
    private function dropIndexIfExists($table, $columns)
    {
        if ($this->indexExists($table, $columns)) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->dropIndex($columns);
            });
        }
    }
};
