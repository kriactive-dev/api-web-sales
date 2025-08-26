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
        Schema::create('option_bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bot_id')->constrained('question_bots')->onDelete('cascade');
            $table->string('label');
            $table->string('value');
            $table->foreignId('next_question_bot_id')->nullable()->constrained('question_bots')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_bots');
    }
};
