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
        Schema::disableForeignKeyConstraints();

        Schema::create('attendances', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('check_in_time')->nullable()->useCurrent();
            $table->unique(['event_id', 'user_id']);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
