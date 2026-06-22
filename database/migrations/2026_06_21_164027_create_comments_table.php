<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // 1. Link to the specific ticket
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            
            // 2. Link to the engineer who wrote it
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 3. The actual text
            $table->text('body');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};