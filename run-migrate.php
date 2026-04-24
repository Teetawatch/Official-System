<?php

/**
 * Laravel Database Update Script for Shared Hosting
 * This script runs 'migrate --force' and 'db:seed --class=BadgeSeeder'
 */

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

// 1. Load Laravel Bootstrap
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');
echo "<html><body style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
echo "<h1 style='color: #4F46E5;'>🚀 Laravel Database Update</h1>";
echo "<hr style='border: 0; border-top: 1px solid #eee; margin-bottom: 20px;'>";

try {
    // 2. Run Migration
    echo "<div style='background: #F3F4F6; padding: 15px; border-radius: 8px; margin-bottom: 15px;'>";
    echo "<strong style='color: #1F2937;'>1. Running Migrations...</strong><br>";
    Artisan::call('migrate', ['--force' => true]);
    echo "<pre style='background: #000; color: #ADF; padding: 10px; border-radius: 5px; margin-top: 10px;'>" . Artisan::output() . "</pre>";
    echo "</div>";

    // 3. Run Seeder
    echo "<div style='background: #F3F4F6; padding: 15px; border-radius: 8px; margin-bottom: 15px;'>";
    echo "<strong style='color: #1F2937;'>2. Running BadgeSeeder...</strong><br>";
    Artisan::call('db:seed', ['--class' => 'BadgeSeeder', '--force' => true]);
    echo "<pre style='background: #000; color: #ADF; padding: 10px; border-radius: 5px; margin-top: 10px;'>" . Artisan::output() . "</pre>";
    echo "</div>";

    echo "<div style='background: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 15px; border-radius: 8px;'>";
    echo "<strong>✅ สำเร็จ!</strong> ฐานข้อมูลของคุณถูกอัปเดตเป็นเวอร์ชันล่าสุดแล้ว";
    echo "</div>";
    
    echo "<p style='color: #DC2626; font-weight: bold; margin-top: 20px;'>⚠️ คำเตือน: กรุณาลบไฟล์ 'run-migrate.php' ออกจาก Server ทันทีเพื่อความปลอดภัย</p>";

} catch (\Exception $e) {
    echo "<div style='background: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; padding: 15px; border-radius: 8px;'>";
    echo "<strong>❌ เกิดข้อผิดพลาด:</strong><br>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "</div>";
}

echo "</body></html>";
