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
        // 1. Create Tournaments Table
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open, ongoing, completed
            $table->timestamp('start_date')->nullable();
            $table->integer('max_participants')->default(16);
            $table->foreignId('champion_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 2. Create Tournament Participants Table
        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('seed')->nullable(); // For future seeding
            $table->timestamp('joined_at')->useCurrent();
            $table->unique(['tournament_id', 'user_id']); // One user per tournament
        });

        // 3. Add Tournament columns to typing_matches
        Schema::table('typing_matches', function (Blueprint $table) {
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments')->onDelete('cascade');
            $table->integer('round')->nullable(); // 1=RO16, 2=QF, 3=SF, 4=Final
            $table->integer('bracket_index')->nullable(); // Position in the bracket (vertical slot)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('typing_matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropColumn(['tournament_id', 'round', 'bracket_index']);
        });

        Schema::dropIfExists('tournament_participants');
        Schema::dropIfExists('tournaments');
    }
};
