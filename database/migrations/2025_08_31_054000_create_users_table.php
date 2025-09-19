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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 50)->unique()->nullable();
            $table->string('full_name', 255);
            $table->string('specialty')->nullable()->comment('Menyimpan kategori utama: Mineral, Batu Bara, Umum, dll.');
            $table->string('position', 100)->nullable();
            $table->string('division', 100)->nullable();
            $table->string('institution')->nullable()->default('PSDMBP');
            $table->string('email', 255)->unique();
            $table->string('phone_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ["admin", "participant"])->default('participant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};