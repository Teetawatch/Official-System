-- SQL สำหรับเพิ่มระบบสมัครสมาชิกนักเรียน
-- วันที่: 2026-01-14
-- ใช้กับตาราง users

-- 0. แก้ไข password column ให้เป็น nullable (สำคัญมาก!)
ALTER TABLE `users` MODIFY `password` VARCHAR(255) NULL;

-- 1. เพิ่มคอลัมน์ is_registered (สถานะลงทะเบียน)
ALTER TABLE `users` ADD `is_registered` TINYINT(1) NOT NULL DEFAULT 0 AFTER `avatar`;

-- 2. เพิ่มคอลัมน์ username (ชื่อผู้ใช้สำหรับ login)
ALTER TABLE `users` ADD `username` VARCHAR(255) NULL AFTER `email`;

-- 3. เพิ่ม unique index สำหรับ username
ALTER TABLE `users` ADD UNIQUE INDEX `users_username_unique` (`username`);

-- 4. อัพเดต users ที่มี password อยู่แล้วให้เป็น is_registered = true
UPDATE `users` SET `is_registered` = 1 WHERE `password` IS NOT NULL;

-- หมายเหตุ: 
-- - นักเรียนใหม่ที่ import เข้ามาจะมี is_registered = 0 และ password = NULL
-- - นักเรียนจะต้องไปหน้า /typing/register เพื่อเลือกชื่อตัวเองและตั้ง username/password
-- - หลังจากลงทะเบียน is_registered จะเปลี่ยนเป็น 1
