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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location', 255);
            $table->string('status', 20)->default('Terjadwal');
            $table->timestamps();
        });

        // Add CHECK constraint for status values (PostgreSQL compatible)
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_status_check CHECK (status IN ('Terjadwal', 'Berlangsung', 'Selesai', 'Dibatalkan'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};