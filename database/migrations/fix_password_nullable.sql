-- SQL เพิ่มเติม: แก้ไขให้ password เป็น nullable
-- ต้อง run ก่อน import นักเรียน

-- แก้ไข password column ให้เป็น nullable
ALTER TABLE `users` MODIFY `password` VARCHAR(255) NULL;
