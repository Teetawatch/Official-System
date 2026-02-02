<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->string('chapter')->nullable()->after('title')->comment('Chapter name or number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->dropColumn('chapter');
        });
    }
};
