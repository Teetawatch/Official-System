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
        // Add submission_type to assignments (typing = พิมพ์, file = แนบไฟล์)
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->enum('submission_type', ['typing', 'file'])->default('typing')->after('type');
        });

        // Add file_path to submissions for file uploads
        Schema::table('typing_submissions', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('keystrokes_data');
            $table->string('file_name')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->dropColumn('submission_type');
        });

        Schema::table('typing_submissions', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name']);
        });
    }
};
