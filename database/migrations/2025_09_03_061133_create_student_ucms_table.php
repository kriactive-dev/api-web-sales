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
        Schema::create('student_ucms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('situacao_academica')->nullable();
            $table->string('situacao_financeira')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_ucms');
    }
};
