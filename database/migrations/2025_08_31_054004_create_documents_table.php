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
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // Use Laravel's built-in method for primary key
            $table->unsignedBigInteger('event_id'); // Match the data type of events.id
            $table->unsignedBigInteger('uploader_id'); // Match the data type of users.id
            $table->string('title', 255);
            $table->enum('type', ["Notulensi", "Materi", "Foto", "Video"]);
            $table->text('content')->nullable()->comment('Digunakan untuk menyimpan isi notulensi');
            $table->string('file_path', 255)->nullable()->comment('Digunakan untuk menyimpan path/URL file');
            $table->timestamps(); // This creates both created_at and updated_at columns

            // Define foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
