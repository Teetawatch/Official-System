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
        // Add columns to existing users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email'); // 'admin', 'student'
            $table->string('student_id')->nullable()->after('role');
            $table->string('class_name')->nullable()->after('student_id');
        });

        // Create typing_assignments table
        Schema::create('typing_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content'); 
            $table->string('type')->default('internal'); // internal, external, command, memo
            $table->integer('difficulty_level')->default(1);
            $table->integer('max_score')->default(100);
            $table->boolean('is_active')->default(true);
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create typing_submissions table
        Schema::create('typing_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_id')->constrained('typing_assignments')->onDelete('cascade');
            $table->integer('wpm');
            $table->float('accuracy');
            $table->integer('time_taken'); // in seconds
            $table->float('score');
            $table->json('keystrokes_data')->nullable(); // For playback/analysis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typing_submissions');
        Schema::dropIfExists('typing_assignments');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'student_id', 'class_name']);
        });
    }
};
