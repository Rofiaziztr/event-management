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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // Use Laravel's built-in method for primary key
            $table->unsignedBigInteger('event_id'); // Match the data type of events.id
            $table->unsignedBigInteger('user_id'); // Match the data type of users.id
            $table->timestamp('check_in_time')->nullable()->useCurrent();
            $table->timestamps();

            // Define foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add unique constraint to prevent duplicate check-ins
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
