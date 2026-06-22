<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Adds the severity column right after the description
            $table->string('severity')->default('Medium')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // This allows us to safely undo the change if we make a mistake
            $table->dropColumn('severity');
        });
    }
};