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
        // Reward Items Table
        Schema::create('reward_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อรางวัล
            $table->text('description')->nullable(); // คำอธิบาย
            $table->enum('type', ['avatar_frame', 'theme', 'title']); // ประเภทรางวัล
            $table->integer('price'); // ราคา (points/coins)
            $table->string('image')->nullable(); // รูปภาพสำหรับแสดง
            $table->json('data')->nullable(); // ข้อมูลเพิ่มเติม (CSS, icon, etc.)
            $table->string('rarity')->default('common'); // common, rare, epic, legendary
            $table->boolean('is_active')->default(true);
            $table->integer('stock')->nullable(); // null = unlimited
            $table->timestamps();
        });

        // User Rewards (Purchased/Owned Items)
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reward_item_id')->constrained()->onDelete('cascade');
            $table->boolean('is_equipped')->default(false); // กำลังใช้งานอยู่หรือไม่
            $table->timestamp('purchased_at');
            $table->timestamps();

            $table->unique(['user_id', 'reward_item_id']); // ซื้อได้ครั้งเดียวต่อไอเทม
        });

        // Add columns to users table for equipped items and coins
        Schema::table('users', function (Blueprint $table) {
            $table->integer('coins')->default(0)->after('points');
            $table->string('equipped_frame')->nullable()->after('coins'); // ID ของ frame ที่ใช้
            $table->string('equipped_theme')->nullable()->after('equipped_frame'); // ID ของ theme ที่ใช้
            $table->string('equipped_title')->nullable()->after('equipped_theme'); // ID ของ title ที่ใช้
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rewards');
        Schema::dropIfExists('reward_items');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['coins', 'equipped_frame', 'equipped_theme', 'equipped_title']);
        });
    }
};
