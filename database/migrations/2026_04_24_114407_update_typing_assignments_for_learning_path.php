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
            $table->integer('level')->default(1)->after('id');
            $table->unsignedBigInteger('prerequisite_id')->nullable()->after('level');
            $table->float('required_score')->default(50)->after('max_score');

            $table->foreign('prerequisite_id')->references('id')->on('typing_assignments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_assignments', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_id']);
            $table->dropColumn(['level', 'prerequisite_id', 'required_score']);
        });
    }
};
