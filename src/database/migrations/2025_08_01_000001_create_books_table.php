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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn')->unique();
            $table->integer('page_count')->nullable();
            $table->timestamp('published_date')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->enum('status', ['PUBLISH', 'MEAP'])->default('PUBLISH');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
