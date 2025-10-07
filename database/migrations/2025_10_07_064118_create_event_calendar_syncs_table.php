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
        Schema::create('event_calendar_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('google_event_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamp('last_sync_attempt')->nullable();
            $table->string('sync_status')->default('pending'); // pending, synced, failed
            $table->text('sync_error')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_calendar_syncs');
    }
};
