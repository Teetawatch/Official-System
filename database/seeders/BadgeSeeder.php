<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'slug' => 'accuracy-master',
                'name' => 'เจ้าแห่งความแม่นยำ',
                'description' => 'พิมพ์ได้ถูกต้องแม่นยำ 100%',
                'icon' => 'fa-bullseye',
                'type' => 'accuracy',
                'value' => 100,
            ],
            [
                'slug' => 'speed-demon',
                'name' => 'เจ้าแห่งความเร็ว',
                'description' => 'พิมพ์ได้ความเร็วมากกว่า 60 WPM',
                'icon' => 'fa-bolt',
                'type' => 'wpm',
                'value' => 60,
            ],
            [
                'slug' => 'iron-finger',
                'name' => 'นักพิมพ์นิ้วเหล็ก',
                'description' => 'สะสมเวลาการพิมพ์ครบ 1 ชั่วโมง',
                'icon' => 'fa-hand-rock',
                'type' => 'cumulative_time',
                'value' => 3600,
            ],
            [
                'slug' => 'persistence-master',
                'name' => 'ปรมาจารย์ความเพียร',
                'description' => 'ส่งงานครบ 10 ชิ้น',
                'icon' => 'fa-fire',
                'type' => 'submission_count',
                'value' => 10,
            ],
        ];

        foreach ($badges as $badge) {
            \App\Models\Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
