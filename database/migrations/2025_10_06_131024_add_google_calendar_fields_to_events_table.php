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
        Schema::table('events', function (Blueprint $table) {
            $table->string('google_calendar_event_id')->nullable()->index();
            $table->string('google_calendar_link')->nullable();
            $table->string('google_conference_link')->nullable();
            $table->timestamp('google_calendar_synced_at')->nullable();
            $table->string('google_calendar_sync_status')->default('never');
            $table->text('google_calendar_last_error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'google_calendar_event_id',
                'google_calendar_link',
                'google_conference_link',
                'google_calendar_synced_at',
                'google_calendar_sync_status',
                'google_calendar_last_error',
            ]);
        });
    }
};
