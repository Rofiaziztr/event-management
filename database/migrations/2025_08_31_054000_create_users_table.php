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
            $table->integer('id')->primary()->autoIncrement();
            $table->string('nip', 50)->unique();
            $table->string('full_name', 255);
            $table->string('position', 100)->nullable();
            $table->string('work_unit', 100)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255)->comment('Simpan password yang sudah di-hash');
            $table->enum('role', ["admin","peserta"])->default('peserta');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
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
