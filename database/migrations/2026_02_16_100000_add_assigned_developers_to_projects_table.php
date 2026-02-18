<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('backend_assigned_to')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->foreignId('frontend_assigned_to')->nullable()->after('backend_assigned_to')->constrained('users')->onDelete('set null');
            $table->foreignId('server_assigned_to')->nullable()->after('frontend_assigned_to')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['backend_assigned_to']);
            $table->dropForeign(['frontend_assigned_to']);
            $table->dropForeign(['server_assigned_to']);
        });
    }
};
