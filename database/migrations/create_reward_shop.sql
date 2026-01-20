-- Add Reward Shop System
-- Run this SQL on your hosting database to add the Reward Shop feature

-- 1. Create reward_items table
CREATE TABLE IF NOT EXISTS `reward_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `type` ENUM('avatar_frame', 'theme', 'title') NOT NULL,
    `price` INT NOT NULL,
    `image` VARCHAR(255) NULL,
    `data` JSON NULL,
    `rarity` VARCHAR(255) NOT NULL DEFAULT 'common',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `stock` INT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create user_rewards table (pivot table)
CREATE TABLE IF NOT EXISTS `user_rewards` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `reward_item_id` BIGINT UNSIGNED NOT NULL,
    `is_equipped` TINYINT(1) NOT NULL DEFAULT 0,
    `purchased_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `user_rewards_user_id_reward_item_id_unique` (`user_id`, `reward_item_id`),
    CONSTRAINT `user_rewards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `user_rewards_reward_item_id_foreign` FOREIGN KEY (`reward_item_id`) REFERENCES `reward_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Add columns to users table
ALTER TABLE `users` 
    ADD COLUMN IF NOT EXISTS `coins` INT NOT NULL DEFAULT 0 AFTER `points`,
    ADD COLUMN IF NOT EXISTS `equipped_frame` VARCHAR(255) NULL AFTER `coins`,
    ADD COLUMN IF NOT EXISTS `equipped_theme` VARCHAR(255) NULL AFTER `equipped_frame`,
    ADD COLUMN IF NOT EXISTS `equipped_title` VARCHAR(255) NULL AFTER `equipped_theme`;

-- 4. Insert default reward items

-- Avatar Frames
INSERT INTO `reward_items` (`name`, `description`, `type`, `price`, `rarity`, `data`, `is_active`, `created_at`, `updated_at`) VALUES
('กรอบเริ่มต้น', 'กรอบอวาตาร์พื้นฐานสำหรับผู้เริ่มต้น', 'avatar_frame', 50, 'common', '{"gradient": "from-gray-400 to-gray-500", "icon": "⭐"}', 1, NOW(), NOW()),
('กรอบสีฟ้า', 'กรอบสีฟ้าสดใส เหมาะสำหรับทุกคน', 'avatar_frame', 100, 'common', '{"gradient": "from-blue-400 to-blue-500", "icon": "💙"}', 1, NOW(), NOW()),
('กรอบสีชมพู', 'กรอบสีชมพูน่ารัก หวานใจ', 'avatar_frame', 100, 'common', '{"gradient": "from-pink-400 to-pink-500", "icon": "💗"}', 1, NOW(), NOW()),
('กรอบรุ้งสวรรค์', 'กรอบหลากสีเหมือนรุ้งกินน้ำ', 'avatar_frame', 300, 'rare', '{"gradient": "from-red-400 via-yellow-400 to-blue-400", "icon": "🌈"}', 1, NOW(), NOW()),
('กรอบมหาสมุทร', 'กรอบสีน้ำทะเลลึกล้ำ', 'avatar_frame', 350, 'rare', '{"gradient": "from-cyan-500 via-blue-600 to-indigo-600", "icon": "🌊"}', 1, NOW(), NOW()),
('กรอบเปลวเพลิง', 'กรอบไฟลุกโชติช่วง แสดงความเร่าร้อน', 'avatar_frame', 600, 'epic', '{"gradient": "from-orange-500 via-red-500 to-rose-600", "icon": "🔥"}', 1, NOW(), NOW()),
('กรอบจักรวาล', 'กรอบสีม่วงเหมือนกาแล็กซี่', 'avatar_frame', 700, 'epic', '{"gradient": "from-purple-600 via-violet-600 to-indigo-700", "icon": "🌌"}', 1, NOW(), NOW()),
('กรอบราชัน', 'กรอบทองคำอร่าม สง่างามดุจราชา', 'avatar_frame', 1500, 'legendary', '{"gradient": "from-yellow-400 via-amber-500 to-orange-500", "icon": "👑"}', 1, NOW(), NOW()),
('กรอบเพชรล้ำค่า', 'กรอบสีเงินแวววาวดุจเพชร หายากที่สุด', 'avatar_frame', 2000, 'legendary', '{"gradient": "from-slate-300 via-white to-slate-400", "icon": "💎"}', 1, NOW(), NOW());

-- Themes
INSERT INTO `reward_items` (`name`, `description`, `type`, `price`, `rarity`, `data`, `is_active`, `created_at`, `updated_at`) VALUES
('ธีมฟ้าใส', 'ธีมสีฟ้าสบายตา เหมือนท้องฟ้าในวันสดใส', 'theme', 150, 'common', '{"gradient": "from-blue-100 via-sky-100 to-cyan-100"}', 1, NOW(), NOW()),
('ธีมเขียวธรรมชาติ', 'ธีมสีเขียวสดชื่น เหมือนอยู่กลางป่า', 'theme', 150, 'common', '{"gradient": "from-green-100 via-emerald-100 to-teal-100"}', 1, NOW(), NOW()),
('ธีมพระอาทิตย์ตก', 'ธีมสีส้มอมม่วง สวยงามเหมือนพระอาทิตย์ลับขอบฟ้า', 'theme', 400, 'rare', '{"gradient": "from-orange-200 via-pink-200 to-purple-200"}', 1, NOW(), NOW()),
('ธีมเปลือกหอย', 'ธีมสีพาสเทลอ่อนหวาน', 'theme', 450, 'rare', '{"gradient": "from-pink-100 via-purple-100 to-indigo-100"}', 1, NOW(), NOW()),
('ธีมแสงเหนือ', 'ธีมสีเขียวฟ้าเหมือน Aurora ใต้ฟ้าขั้วโลก', 'theme', 800, 'epic', '{"gradient": "from-green-300 via-cyan-300 to-purple-300"}', 1, NOW(), NOW()),
('ธีมลาวา', 'ธีมสีแดงส้มร้อนแรง เหมือนลาวาภูเขาไฟ', 'theme', 850, 'epic', '{"gradient": "from-red-300 via-orange-300 to-yellow-200"}', 1, NOW(), NOW()),
('ธีมดวงดาว', 'ธีมสีม่วงดำ เหมือนกลางดวงดาวยามค่ำคืน', 'theme', 1800, 'legendary', '{"gradient": "from-indigo-900 via-purple-800 to-pink-700"}', 1, NOW(), NOW());

-- Titles
INSERT INTO `reward_items` (`name`, `description`, `type`, `price`, `rarity`, `data`, `is_active`, `created_at`, `updated_at`) VALUES
('นักพิมพ์ฝึกหัด', 'ตำแหน่งสำหรับผู้เริ่มต้น', 'title', 100, 'common', '{"emoji": "🌱"}', 1, NOW(), NOW()),
('นักพิมพ์ขยัน', 'สำหรับคนที่ส่งงานตรงเวลาเสมอ', 'title', 150, 'common', '{"emoji": "📝"}', 1, NOW(), NOW()),
('นักพิมพ์มือไว', 'พิมพ์เร็วปาน 10 นิ้ว', 'title', 350, 'rare', '{"emoji": "⚡"}', 1, NOW(), NOW()),
('นักพิมพ์แม่นยำ', 'ความแม่นยำ 100% ทุกครั้ง', 'title', 400, 'rare', '{"emoji": "🎯"}', 1, NOW(), NOW()),
('เจ้าแห่งคีย์บอร์ด', 'ผู้พิชิตคีย์บอร์ดทุกรูปแบบ', 'title', 450, 'rare', '{"emoji": "⌨️"}', 1, NOW(), NOW()),
('นักพิมพ์มือทอง', 'นิ้วทองคำ พิมพ์ทุกตัวไม่พลาด', 'title', 750, 'epic', '{"emoji": "🌟"}', 1, NOW(), NOW()),
('จอมพิมพ์กระหน่ำ', 'พิมพ์รัวๆ หยุดไม่ได้', 'title', 800, 'epic', '{"emoji": "🔥"}', 1, NOW(), NOW()),
('ราชาสนามแข่ง', 'ผู้ชนะ 1v1 ทุกสมรภูมิ', 'title', 900, 'epic', '{"emoji": "🏆"}', 1, NOW(), NOW()),
('ตำนานแห่งการพิมพ์', 'ผู้ที่พิสูจน์ตัวเองจนกลายเป็นตำนาน', 'title', 2000, 'legendary', '{"emoji": "👑"}', 1, NOW(), NOW()),
('เทพแห่งนิ้วมือ', 'ผู้มีฝีมือการพิมพ์ระดับเทพ', 'title', 2500, 'legendary', '{"emoji": "✨"}', 1, NOW(), NOW()),
('จักรพรรดิพิมพ์ดีด', 'ผู้ปกครองแห่งโลกการพิมพ์', 'title', 3000, 'legendary', '{"emoji": "🐉"}', 1, NOW(), NOW());

-- Done!
SELECT 'Reward Shop system installed successfully!' AS message;
