-- =====================================================
-- إضافة أعمدة name و note إلى جدول extracted_phones
-- =====================================================
-- تاريخ الإنشاء: 2025-10-31
-- الوصف: إضافة عمود الاسم (name) وعمود العنوان/الملاحظات (note)
-- =====================================================

-- الطريقة البسيطة: مباشرة (إذا كان العمود موجوداً، سيظهر خطأ لكن لا بأس)
-- إضافة عمود name
ALTER TABLE `extracted_phones` 
ADD COLUMN `name` VARCHAR(255) NULL AFTER `phone`;

-- إضافة عمود note (العنوان/الملاحظات)
ALTER TABLE `extracted_phones` 
ADD COLUMN `note` TEXT NULL AFTER `name`;

-- =====================================================
-- الطريقة الثانية: مع التحقق (لأنواع MySQL القديمة التي لا تدعم IF NOT EXISTS)
-- =====================================================
-- إذا كان MySQL الخاص بك لا يدعم IF NOT EXISTS، استخدم هذا:

-- SET @dbname = DATABASE();
-- SET @tablename = 'extracted_phones';
-- SET @columnname1 = 'name';
-- SET @columnname2 = 'note';
-- SET @preparedStatement = (SELECT IF(
--   (
--     SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
--     WHERE
--       (TABLE_SCHEMA = @dbname)
--       AND (TABLE_NAME = @tablename)
--       AND (COLUMN_NAME = @columnname1)
--   ) > 0,
--   'SELECT 1',
--   CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname1, ' VARCHAR(255) NULL AFTER phone')
-- ));
-- PREPARE alterIfNotExists FROM @preparedStatement;
-- EXECUTE alterIfNotExists;
-- DEALLOCATE PREPARE alterIfNotExists;

-- SET @preparedStatement = (SELECT IF(
--   (
--     SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
--     WHERE
--       (TABLE_SCHEMA = @dbname)
--       AND (TABLE_NAME = @tablename)
--       AND (COLUMN_NAME = @columnname2)
--   ) > 0,
--   'SELECT 1',
--   CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname2, ' TEXT NULL AFTER name')
-- ));
-- PREPARE alterIfNotExists FROM @preparedStatement;
-- EXECUTE alterIfNotExists;
-- DEALLOCATE PREPARE alterIfNotExists;

-- =====================================================
-- التحقق من النتيجة
-- =====================================================
-- يمكنك تشغيل الاستعلام التالي للتحقق من بنية الجدول:
-- DESCRIBE extracted_phones;

-- أو:
-- SHOW COLUMNS FROM extracted_phones;

-- =====================================================
-- ملاحظات:
-- =====================================================
-- 1. name: VARCHAR(255) NULL - يمكن أن يكون فارغاً
-- 2. note: TEXT NULL - يمكن أن يكون فارغاً، مناسب للنصوص الطويلة
-- 3. تم وضع name بعد phone و note بعد name
-- 4. إذا ظهرت رسالة خطأ بأن العمود موجود بالفعل، يمكنك تخطي هذا الأمر
-- =====================================================

