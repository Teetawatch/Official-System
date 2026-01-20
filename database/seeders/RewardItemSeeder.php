<?php

namespace Database\Seeders;

use App\Models\RewardItem;
use Illuminate\Database\Seeder;

class RewardItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================== AVATAR FRAMES ====================
        $avatarFrames = [
            // Common Frames
            [
                'name' => 'กรอบเริ่มต้น',
                'description' => 'กรอบอวาตาร์พื้นฐานสำหรับผู้เริ่มต้น',
                'type' => 'avatar_frame',
                'price' => 50,
                'rarity' => 'common',
                'data' => ['gradient' => 'from-gray-400 to-gray-500', 'icon' => '⭐'],
            ],
            [
                'name' => 'กรอบสีฟ้า',
                'description' => 'กรอบสีฟ้าสดใส เหมาะสำหรับทุกคน',
                'type' => 'avatar_frame',
                'price' => 100,
                'rarity' => 'common',
                'data' => ['gradient' => 'from-blue-400 to-blue-500', 'icon' => '💙'],
            ],
            [
                'name' => 'กรอบสีชมพู',
                'description' => 'กรอบสีชมพูน่ารัก หวานใจ',
                'type' => 'avatar_frame',
                'price' => 100,
                'rarity' => 'common',
                'data' => ['gradient' => 'from-pink-400 to-pink-500', 'icon' => '💗'],
            ],
            // Rare Frames
            [
                'name' => 'กรอบรุ้งสวรรค์',
                'description' => 'กรอบหลากสีเหมือนรุ้งกินน้ำ',
                'type' => 'avatar_frame',
                'price' => 300,
                'rarity' => 'rare',
                'data' => ['gradient' => 'from-red-400 via-yellow-400 to-blue-400', 'icon' => '🌈'],
            ],
            [
                'name' => 'กรอบมหาสมุทร',
                'description' => 'กรอบสีน้ำทะเลลึกล้ำ',
                'type' => 'avatar_frame',
                'price' => 350,
                'rarity' => 'rare',
                'data' => ['gradient' => 'from-cyan-500 via-blue-600 to-indigo-600', 'icon' => '🌊'],
            ],
            // Epic Frames
            [
                'name' => 'กรอบเปลวเพลิง',
                'description' => 'กรอบไฟลุกโชติช่วง แสดงความเร่าร้อน',
                'type' => 'avatar_frame',
                'price' => 600,
                'rarity' => 'epic',
                'data' => ['gradient' => 'from-orange-500 via-red-500 to-rose-600', 'icon' => '🔥'],
            ],
            [
                'name' => 'กรอบจักรวาล',
                'description' => 'กรอบสีม่วงเหมือนกาแล็กซี่',
                'type' => 'avatar_frame',
                'price' => 700,
                'rarity' => 'epic',
                'data' => ['gradient' => 'from-purple-600 via-violet-600 to-indigo-700', 'icon' => '🌌'],
            ],
            // Legendary Frames
            [
                'name' => 'กรอบราชัน',
                'description' => 'กรอบทองคำอร่าม สง่างามดุจราชา',
                'type' => 'avatar_frame',
                'price' => 1500,
                'rarity' => 'legendary',
                'data' => ['gradient' => 'from-yellow-400 via-amber-500 to-orange-500', 'icon' => '👑'],
            ],
            [
                'name' => 'กรอบเพชรล้ำค่า',
                'description' => 'กรอบสีเงินแวววาวดุจเพชร หายากที่สุด',
                'type' => 'avatar_frame',
                'price' => 2000,
                'rarity' => 'legendary',
                'data' => ['gradient' => 'from-slate-300 via-white to-slate-400', 'icon' => '💎'],
            ],
        ];

        // ==================== THEMES ====================
        $themes = [
            // Common Themes
            [
                'name' => 'ธีมฟ้าใส',
                'description' => 'ธีมสีฟ้าสบายตา เหมือนท้องฟ้าในวันสดใส',
                'type' => 'theme',
                'price' => 150,
                'rarity' => 'common',
                'data' => ['gradient' => 'from-blue-100 via-sky-100 to-cyan-100'],
            ],
            [
                'name' => 'ธีมเขียวธรรมชาติ',
                'description' => 'ธีมสีเขียวสดชื่น เหมือนอยู่กลางป่า',
                'type' => 'theme',
                'price' => 150,
                'rarity' => 'common',
                'data' => ['gradient' => 'from-green-100 via-emerald-100 to-teal-100'],
            ],
            // Rare Themes
            [
                'name' => 'ธีมพระอาทิตย์ตก',
                'description' => 'ธีมสีส้มอมม่วง สวยงามเหมือนพระอาทิตย์ลับขอบฟ้า',
                'type' => 'theme',
                'price' => 400,
                'rarity' => 'rare',
                'data' => ['gradient' => 'from-orange-200 via-pink-200 to-purple-200'],
            ],
            [
                'name' => 'ธีมเปลือกหอย',
                'description' => 'ธีมสีพาสเทลอ่อนหวาน',
                'type' => 'theme',
                'price' => 450,
                'rarity' => 'rare',
                'data' => ['gradient' => 'from-pink-100 via-purple-100 to-indigo-100'],
            ],
            // Epic Themes
            [
                'name' => 'ธีมแสงเหนือ',
                'description' => 'ธีมสีเขียวฟ้าเหมือน Aurora ใต้ฟ้าขั้วโลก',
                'type' => 'theme',
                'price' => 800,
                'rarity' => 'epic',
                'data' => ['gradient' => 'from-green-300 via-cyan-300 to-purple-300'],
            ],
            [
                'name' => 'ธีมลาวา',
                'description' => 'ธีมสีแดงส้มร้อนแรง เหมือนลาวาภูเขาไฟ',
                'type' => 'theme',
                'price' => 850,
                'rarity' => 'epic',
                'data' => ['gradient' => 'from-red-300 via-orange-300 to-yellow-200'],
            ],
            // Legendary Themes
            [
                'name' => 'ธีมดวงดาว',
                'description' => 'ธีมสีม่วงดำ เหมือนกลางดวงดาวยามค่ำคืน',
                'type' => 'theme',
                'price' => 1800,
                'rarity' => 'legendary',
                'data' => ['gradient' => 'from-indigo-900 via-purple-800 to-pink-700'],
            ],
        ];

        // ==================== TITLES ====================
        $titles = [
            // Common Titles
            [
                'name' => 'นักพิมพ์ฝึกหัด',
                'description' => 'ตำแหน่งสำหรับผู้เริ่มต้น',
                'type' => 'title',
                'price' => 100,
                'rarity' => 'common',
                'data' => ['emoji' => '🌱'],
            ],
            [
                'name' => 'นักพิมพ์ขยัน',
                'description' => 'สำหรับคนที่ส่งงานตรงเวลาเสมอ',
                'type' => 'title',
                'price' => 150,
                'rarity' => 'common',
                'data' => ['emoji' => '📝'],
            ],
            // Rare Titles
            [
                'name' => 'นักพิมพ์มือไว',
                'description' => 'พิมพ์เร็วปาน 10 นิ้ว',
                'type' => 'title',
                'price' => 350,
                'rarity' => 'rare',
                'data' => ['emoji' => '⚡'],
            ],
            [
                'name' => 'นักพิมพ์แม่นยำ',
                'description' => 'ความแม่นยำ 100% ทุกครั้ง',
                'type' => 'title',
                'price' => 400,
                'rarity' => 'rare',
                'data' => ['emoji' => '🎯'],
            ],
            [
                'name' => 'เจ้าแห่งคีย์บอร์ด',
                'description' => 'ผู้พิชิตคีย์บอร์ดทุกรูปแบบ',
                'type' => 'title',
                'price' => 450,
                'rarity' => 'rare',
                'data' => ['emoji' => '⌨️'],
            ],
            // Epic Titles
            [
                'name' => 'นักพิมพ์มือทอง',
                'description' => 'นิ้วทองคำ พิมพ์ทุกตัวไม่พลาด',
                'type' => 'title',
                'price' => 750,
                'rarity' => 'epic',
                'data' => ['emoji' => '🌟'],
            ],
            [
                'name' => 'จอมพิมพ์กระหน่ำ',
                'description' => 'พิมพ์รัวๆ หยุดไม่ได้',
                'type' => 'title',
                'price' => 800,
                'rarity' => 'epic',
                'data' => ['emoji' => '🔥'],
            ],
            [
                'name' => 'ราชาสนามแข่ง',
                'description' => 'ผู้ชนะ 1v1 ทุกสมรภูมิ',
                'type' => 'title',
                'price' => 900,
                'rarity' => 'epic',
                'data' => ['emoji' => '🏆'],
            ],
            // Legendary Titles
            [
                'name' => 'ตำนานแห่งการพิมพ์',
                'description' => 'ผู้ที่พิสูจน์ตัวเองจนกลายเป็นตำนาน',
                'type' => 'title',
                'price' => 2000,
                'rarity' => 'legendary',
                'data' => ['emoji' => '👑'],
            ],
            [
                'name' => 'เทพแห่งนิ้วมือ',
                'description' => 'ผู้มีฝีมือการพิมพ์ระดับเทพ',
                'type' => 'title',
                'price' => 2500,
                'rarity' => 'legendary',
                'data' => ['emoji' => '✨'],
            ],
            [
                'name' => 'จักรพรรดิพิมพ์ดีด',
                'description' => 'ผู้ปกครองแห่งโลกการพิมพ์',
                'type' => 'title',
                'price' => 3000,
                'rarity' => 'legendary',
                'data' => ['emoji' => '🐉'],
            ],
        ];

        // Insert all items
        foreach (array_merge($avatarFrames, $themes, $titles) as $item) {
            RewardItem::updateOrCreate(
                ['name' => $item['name'], 'type' => $item['type']],
                $item
            );
        }

        $this->command->info('✅ Seeded ' . count($avatarFrames) . ' avatar frames');
        $this->command->info('✅ Seeded ' . count($themes) . ' themes');
        $this->command->info('✅ Seeded ' . count($titles) . ' titles');
        $this->command->info('🎉 Total: ' . (count($avatarFrames) + count($themes) + count($titles)) . ' reward items');
    }
}
