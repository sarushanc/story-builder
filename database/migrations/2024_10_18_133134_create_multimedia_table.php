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
        Schema::create('multimedia', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable'); // for polymorphic relation
            $table->string('file_path'); // Path to the file
            $table->string('file_type'); // Type of file: image, video, audio
            $table->integer('file_size')->nullable(); // Optional, size in KB/MB
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};
