<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // We added nullable() because not every single comment will have an image.
            // It allows this column to be empty without crashing the database.
            $table->string('file_path')->nullable()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
};