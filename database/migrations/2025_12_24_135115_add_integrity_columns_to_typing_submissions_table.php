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
        Schema::table('typing_submissions', function (Blueprint $table) {
            $table->string('file_hash')->nullable()->after('file_name');
            $table->json('file_metadata')->nullable()->after('file_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_submissions', function (Blueprint $table) {
            $table->dropColumn(['file_hash', 'file_metadata']);
        });
    }
};
