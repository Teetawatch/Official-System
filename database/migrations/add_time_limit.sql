-- Add time_limit column to typing_assignments table
-- For Shared Hosting: Run this via phpMyAdmin or MySQL command line

ALTER TABLE `typing_assignments` 
ADD COLUMN `time_limit` INT(11) NULL 
COMMENT 'Time limit in minutes for typing practice' 
AFTER `max_score`;

-- Insert into migrations table to mark as done
INSERT INTO `migrations` (`migration`, `batch`) 
VALUES ('2026_01_14_080000_add_time_limit_to_typing_assignments_table', 
        (SELECT IFNULL(MAX(`batch`), 0) + 1 FROM `migrations` AS m));
