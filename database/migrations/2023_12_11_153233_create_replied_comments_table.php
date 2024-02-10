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
        Schema::create('replied_comments', function (Blueprint $table) {
            $table->id();
            $table->foreign('commentId')->references('id')->on('comments')->onDelete('cascade');
            $table->unsignedBigInteger('commentId');
            $table->foreign('replierId')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('replierId');
            $table->string('repliedComment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replied_comments');
    }
};
