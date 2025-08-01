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
            $table->string('cover_image')->nullable(); 
            $table->string('isbn')->unique()->nullable();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();                   
            $table->foreignId('book_category_id')->constrained()->onDelete('cascade');
            $table->integer('copies')->default(1);
            $table->enum('availability', ['available', 'out of stock'])->default('available');
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
