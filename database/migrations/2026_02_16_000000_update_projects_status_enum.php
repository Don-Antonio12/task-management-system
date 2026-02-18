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
        // Modify the status column to include 'overdue'
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed', 'archived', 'overdue'])->change()->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed', 'archived'])->change()->default('active');
        });
    }
};
