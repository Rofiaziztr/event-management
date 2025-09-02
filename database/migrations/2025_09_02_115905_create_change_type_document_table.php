<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            /**
             * Mengubah tipe kolom 'type' dari ENUM menjadi STRING.
             * Ini memberikan fleksibilitas lebih dan menghindari masalah 'Data Truncated'
             * pada beberapa konfigurasi MySQL. Validasi tetap dijaga di level aplikasi.
             */
            $table->string('type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Perhatian: Proses revert ini akan gagal jika ada data di kolom 'type'
            // yang tidak sesuai dengan daftar ENUM yang didefinisikan.
            $table->enum('type', ["Notulensi", "Materi", "Foto", "Video"])->change();
        });
    }
};
