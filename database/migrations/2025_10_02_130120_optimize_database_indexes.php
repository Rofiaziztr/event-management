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
            if (!$this->indexExists('events', 'events_start_time_index')) {
                $table->index('start_time');
            }
            if (!$this->indexExists('events', 'events_end_time_index')) {
                $table->index('end_time');
            }

            // Composite index for status and date filtering
            if (!$this->indexExists('events', 'events_status_start_time_index')) {
                $table->index(['status', 'start_time']);
            }
            if (!$this->indexExists('events', 'events_status_end_time_index')) {
                $table->index(['status', 'end_time']);
            }
        });

        // Users table - add missing indexes
        Schema::table('users', function (Blueprint $table) {
            // Index for role filtering
            if (!$this->indexExists('users', 'users_role_index')) {
                $table->index('role');
            }

            // Index for division queries
            if (!$this->indexExists('users', 'users_division_index')) {
                $table->index('division');
            }

            // Composite index for role and division
            if (!$this->indexExists('users', 'users_role_division_index')) {
                $table->index(['role', 'division']);
            }
        });

        // Event participants table - add missing indexes
        Schema::table('event_participants', function (Blueprint $table) {
            // Composite index for participant lookups
            if (!$this->indexExists('event_participants', 'event_participants_event_id_user_id_index')) {
                $table->index(['event_id', 'user_id']);
            }
        });

        // Attendances table - add missing indexes
        Schema::table('attendances', function (Blueprint $table) {
            // Index for check-in time queries
            if (!$this->indexExists('attendances', 'attendances_check_in_time_index')) {
                $table->index('check_in_time');
            }

            // Composite index for event attendance queries
            if (!$this->indexExists('attendances', 'attendances_event_id_check_in_time_index')) {
                $table->index(['event_id', 'check_in_time']);
            }
        });

        // Documents table - add missing indexes
        Schema::table('documents', function (Blueprint $table) {
            // Index for uploader queries
            if (!$this->indexExists('documents', 'documents_uploader_id_index')) {
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
        $this->dropIndexIfExists('events', 'events_start_time_index');
        $this->dropIndexIfExists('events', 'events_end_time_index');
        $this->dropIndexIfExists('events', 'events_status_start_time_index');
        $this->dropIndexIfExists('events', 'events_status_end_time_index');

        $this->dropIndexIfExists('users', 'users_role_index');
        $this->dropIndexIfExists('users', 'users_division_index');
        $this->dropIndexIfExists('users', 'users_role_division_index');

        $this->dropIndexIfExists('event_participants', 'event_participants_event_id_user_id_index');

        $this->dropIndexIfExists('attendances', 'attendances_check_in_time_index');
        $this->dropIndexIfExists('attendances', 'attendances_event_id_check_in_time_index');

        $this->dropIndexIfExists('documents', 'documents_uploader_id_index');
    }

    /**
     * Check if index exists using PostgreSQL-compatible query.
     * Works with both PostgreSQL and MySQL.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'pgsql') {
                $result = DB::select(
                    "SELECT 1 FROM pg_indexes WHERE tablename = ? AND indexname = ?",
                    [$table, $indexName]
                );
                return count($result) > 0;
            }

            // MySQL fallback
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            foreach ($indexes as $index) {
                if ($index->Key_name === $indexName) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist to be safe
            return false;
        }
    }

    /**
     * Drop index if it exists.
     */
    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if ($this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }
};
