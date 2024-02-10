<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            $table->dropForeign('sprints_requestid_foreign');
            $table->dropColumn('requestId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sprints', function (Blueprint $table) {
            $table->unsignedBigInteger('requestId')->nullable();
            $table->foreign('requestId')->references('id')->on('change_request')->onDelete('cascade');
        });
    }
};
