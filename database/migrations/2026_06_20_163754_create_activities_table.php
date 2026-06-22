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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            
            // This links the activity to the logged-in user (Bio Details)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // The actual activity details
            $table->string('title');
            $table->text('description')->nullable();
            
            // The status (Pending, In Progress, Completed)
            $table->string('status')->default('pending');
            
            // This automatically creates 'created_at' and 'updated_at' (Timestamps)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};