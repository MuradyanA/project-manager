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
        Schema::create('likes_dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreign('commenterId')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('commenterId');
            $table->integer('likeable_id');
            $table->string('likeable_type');
            $table->enum('action', [1, 0, -1]);
            $table->timestamps();
        });
        //commenterId, commentId, action, 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes_dislikes');
    }
};
