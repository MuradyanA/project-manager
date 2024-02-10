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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projectId');
            $table->foreign('projectId')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('requestId');
            $table->foreign('requestId')->references('id')->on('change_request')->onDelete('cascade');
            $table->foreign('sprintId')->references('id')->on('sprints')->onDelete('cascade');
            $table->unsignedBigInteger('sprintId');
            $table->string('task');
            $table->date('start')->precision(0);
            $table->date('end')->precision(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
