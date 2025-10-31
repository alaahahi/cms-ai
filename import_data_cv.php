<?php

/**
 * سكربت بسيط لاستيراد البيانات إلى جدول data_cv
 * بدون فلترة - فقط إدخال مباشر
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\DataCv;

echo "📥 استيراد البيانات إلى جدول data_cv\n";
echo str_repeat("=", 60) . "\n\n";

// قراءة الملف
$filePath = __DIR__ . '/data_cv.txt'; // يمكن تغيير المسار

// إذا كان المستخدم يريد إدخال المسار يدوياً
if (!file_exists($filePath)) {
    echo "❓ يرجى إدخال مسار الملف (أو ضع الملف باسم data_cv.txt في المجلد الرئيسي): ";
    $inputPath = trim(fgets(STDIN));
    if (!empty($inputPath)) {
        $filePath = $inputPath;
    }
}

if (!file_exists($filePath)) {
    die("❌ لم يتم العثور على الملف: $filePath\n");
}

echo "✅ تم العثور على الملف: $filePath\n";
echo "📊 حجم الملف: " . number_format(filesize($filePath) / 1024, 2) . " KB\n\n";

// قراءة الملف
$handle = fopen($filePath, 'r');

if ($handle === false) {
    die("❌ لا يمكن فتح الملف\n");
}

// قراءة السطر الأول (العناوين) - محاولة Tab أولاً
$headers = fgetcsv($handle, 0, "\t");

// إذا لم تنجح، جرب Comma
if (empty($headers) || count($headers) < 3) {
    rewind($handle);
    $headers = fgetcsv($handle, 0, ",");
}

// إذا لم تنجح، جرب Space (كملاذ أخير)
if (empty($headers) || count($headers) < 3) {
    rewind($handle);
    $line = fgets($handle);
    $headers = preg_split('/\s+/', trim($line));
}

if ($headers === false || empty($headers)) {
    fclose($handle);
    die("❌ لا يمكن قراءة الملف أو الملف فارغ\n");
}

// تنظيف العناوين من المسافات الزائدة
$headers = array_map('trim', $headers);

echo "📋 العناوين المكتشفة: " . implode(' | ', $headers) . "\n\n";

// البحث عن فهرس الأعمدة
$phoneIndex = null;
$nameIndex = null;
$addressIndex = null;

foreach ($headers as $index => $header) {
    $headerLower = strtolower($header);
    if ($phoneIndex === null && (strpos($headerLower, 'phone') !== false || strpos($headerLower, 'رقم') !== false)) {
        $phoneIndex = $index;
    }
    if ($nameIndex === null && (strpos($headerLower, 'name') !== false || strpos($headerLower, 'اسم') !== false)) {
        $nameIndex = $index;
    }
    if ($addressIndex === null && (strpos($headerLower, 'living') !== false || strpos($headerLower, 'address') !== false || strpos($headerLower, 'عنوان') !== false)) {
        $addressIndex = $index;
    }
}

// إذا لم نجد العناوين، نستخدم المواضع الافتراضية (0, 1, 2)
if ($phoneIndex === null) {
    $phoneIndex = 0;
    echo "⚠️  لم يتم العثور على عمود PHONE، سيتم استخدام العمود الأول\n";
}
if ($nameIndex === null) {
    $nameIndex = 1;
    echo "⚠️  لم يتم العثور على عمود NAME، سيتم استخدام العمود الثاني\n";
}
if ($addressIndex === null) {
    $addressIndex = 2;
    echo "⚠️  لم يتم العثور على عمود ADDRESS/LIVING، سيتم استخدام العمود الثالث\n";
}

echo "\n📝 سيتم استخدام الأعمدة:\n";
echo "   - PHONE: العمود " . ($phoneIndex + 1) . "\n";
echo "   - NAME: العمود " . ($nameIndex + 1) . "\n";
echo "   - ADDRESS: العمود " . ($addressIndex + 1) . "\n\n";

// تحديد نوع الفاصل
rewind($handle);
$testLine = fgets($handle);
fseek($handle, 0);

// محاولة اكتشاف الفاصل
$delimiter = "\t"; // افتراضي Tab
if (strpos($testLine, "\t") !== false) {
    $delimiter = "\t";
} elseif (strpos($testLine, ",") !== false) {
    $delimiter = ",";
} else {
    $delimiter = "\t"; // افتراضي
}

// قراءة السطر الأول مرة أخرى بعد إعادة التموضع
fgetcsv($handle, 0, $delimiter);

// قراءة البيانات
$rowCount = 0;
$importedCount = 0;
$errors = [];

echo "🔄 جاري قراءة البيانات (الفاصل: " . ($delimiter === "\t" ? "Tab" : "Comma") . ")...\n\n";

while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
    $rowCount++;
    
    // التحقق من أن الصف يحتوي على البيانات المطلوبة
    if (!isset($row[$phoneIndex]) || empty(trim($row[$phoneIndex]))) {
        continue; // تخطي الصفوف الفارغة
    }
    
    $phoneNumber = trim($row[$phoneIndex] ?? '');
    $name = trim($row[$nameIndex] ?? '');
    $address = trim($row[$addressIndex] ?? '');
    
    // إدخال البيانات مباشرة بدون فلترة
    try {
        DataCv::create([
            'phone_number' => $phoneNumber,
            'name' => $name,
            'address' => $address,
        ]);
        
        $importedCount++;
        
        // عرض التقدم كل 100 سجل
        if ($importedCount % 100 == 0) {
            echo "   ✅ تم استيراد $importedCount سجل...\n";
        }
    } catch (\Exception $e) {
        $errors[] = "الصف $rowCount: " . $e->getMessage();
    }
}

fclose($handle);

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ تم الانتهاء من الاستيراد!\n\n";
echo "📊 النتائج:\n";
echo "   - إجمالي الصفوف المقروءة: $rowCount\n";
echo "   - تم استيراد: $importedCount سجل\n";
echo "   - إجمالي السجلات في قاعدة البيانات: " . DataCv::count() . "\n";

if (!empty($errors)) {
    echo "\n⚠️  الأخطاء:\n";
    foreach (array_slice($errors, 0, 10) as $error) {
        echo "   - $error\n";
    }
    if (count($errors) > 10) {
        echo "   ... والمزيد (" . (count($errors) - 10) . " خطأ آخر)\n";
    }
}

echo "\n✅ تمت العملية بنجاح!\n";

