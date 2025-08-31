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

        Schema::create('documents', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->integer('uploader_id');
            $table->foreign('uploader_id')->references('id')->on('users');
            $table->string('title', 255);
            $table->enum('type', ["Notulensi","Materi","Foto","Video"]);
            $table->text('content')->nullable()->comment('Digunakan untuk menyimpan isi notulensi');
            $table->string('file_path', 255)->nullable()->comment('Digunakan untuk menyimpan path/URL file');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
