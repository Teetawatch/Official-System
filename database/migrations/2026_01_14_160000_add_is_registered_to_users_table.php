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
        if (!Schema::hasColumn('users', 'is_registered')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_registered')->default(false)->after('avatar');
            });
        }

        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->unique()->after('email');
            });
        }

        // Update existing users with passwords to be registered
        \App\Models\User::whereNotNull('password')->update(['is_registered' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_registered');
            $table->dropColumn('username');
        });
    }
};
