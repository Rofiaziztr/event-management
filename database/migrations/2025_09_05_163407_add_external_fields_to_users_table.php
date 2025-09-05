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
        Schema::table('users', function (Blueprint $table) {
            // Jadikan kolom NIP, divisi, dan password nullable
            // karena peserta eksternal tidak akan memilikinya.
            $table->string('nip')->nullable()->change();
            $table->string('division')->nullable()->change();
            $table->string('password')->nullable()->change();

            // Tambahkan kolom baru untuk peserta eksternal
            $table->string('institution')->nullable()->after('division');
            $table->string('phone_number')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
