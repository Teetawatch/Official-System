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
            $table->integer('time_limit')->nullable()->after('max_score')->comment('Time limit in minutes for typing practice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->dropColumn('time_limit');
        });
    }
};
