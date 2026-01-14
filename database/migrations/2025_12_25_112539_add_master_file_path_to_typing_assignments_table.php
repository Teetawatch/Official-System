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
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->string('master_file_path')->nullable()->after('content');
            $table->string('master_file_name')->nullable()->after('master_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->dropColumn(['master_file_path', 'master_file_name']);
        });
    }
};
