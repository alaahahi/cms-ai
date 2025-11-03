-- SQL لتطبيق Migration: إضافة عمود batch_id إلى جدول messages
-- تاريخ: 2025-11-02

-- إضافة عمود batch_id بعد عمود status
ALTER TABLE `messages` 
ADD COLUMN `batch_id` VARCHAR(255) NULL AFTER `status`;

-- التحقق من نجاح العملية
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'messages'
AND COLUMN_NAME = 'batch_id';

