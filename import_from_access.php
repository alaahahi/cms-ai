<?php

/**
 * أداة لاستيراد البيانات من ملف Access بدون معرفة اسم الجدول
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ExtractedPhone;
use App\Jobs\CheckWhatsAppNumber;

// مسار ملف Access
$accessDbPath = __DIR__ . '/public/Zain.accdb';

if (!file_exists($accessDbPath)) {
    die("❌ لم يتم العثور على الملف: $accessDbPath\n");
}

echo "📂 تم العثور على الملف: Zain.accdb\n";
echo "📊 حجم الملف: " . number_format(filesize($accessDbPath) / 1024 / 1024, 2) . " MB\n\n";

try {
    // الاتصال بـ Access Database
    $dsn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$accessDbPath;";
    $pdo = new PDO($dsn);
    
    // جلب جميع الجداول
    echo "🔍 البحث عن الجداول...\n";
    $tables = $pdo->query("SELECT Name FROM MSysObjects WHERE Type=1 AND Flags=0")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        die("❌ لم يتم العثور على أي جدول في قاعدة البيانات\n");
    }
    
    echo "✅ تم العثور على " . count($tables) . " جدول:\n\n";
    
    // فحص كل جدول
    $suitableTables = [];
    
    foreach ($tables as $tableName) {
        echo "   📊 فحص جدول: $tableName\n";
        
        try {
            // جلب عينة من البيانات
            $stmt = $pdo->query("SELECT TOP 5 * FROM [$tableName]");
            $sample = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($sample)) {
                echo "      ⚠️  الجدول فارغ\n";
                continue;
            }
            
            $columns = array_keys($sample[0]);
            echo "      الأعمدة: " . implode(', ', $columns) . "\n";
            
            // البحث عن أعمدة محتملة للأرقام
            $potentialColumns = [];
            foreach ($columns as $col) {
                if (stripos($col, 'phone') !== false || 
                    stripos($col, 'رقم') !== false ||
                    stripos($col, 'tel') !== false ||
                    stripos($col, 'mobile') !== false ||
                    stripos($col, 'number') !== false ||
                    stripos($col, 'رقم') !== false) {
                    $potentialColumns[] = $col;
                }
            }
            
            if (!empty($potentialColumns)) {
                echo "      ✅ عمود محتمل للأرقام: " . implode(', ', $potentialColumns) . "\n";
                $suitableTables[] = [
                    'name' => $tableName,
                    'columns' => $columns,
                    'potential_phone_columns' => $potentialColumns,
                    'sample' => $sample
                ];
            }
            
            // فحص عينة من البيانات للبحث عن أرقام
            foreach ($sample as $row) {
                foreach ($row as $colName => $value) {
                    // التحقق من أن القيمة تبدو كرقم هاتف
                    if (is_string($value) && preg_match('/[0-9]{9,12}/', $value)) {
                        if (!in_array($colName, $potentialColumns)) {
                            echo "      💡 عمود يحتوي على أرقام: $colName\n";
                            if (!in_array($colName, $potentialColumns)) {
                                $potentialColumns[] = $colName;
                            }
                        }
                    }
                }
            }
            
            echo "\n";
            
        } catch (Exception $e) {
            echo "      ❌ خطأ: " . $e->getMessage() . "\n\n";
        }
    }
    
    if (empty($suitableTables)) {
        die("\n❌ لم يتم العثور على جداول مناسبة\n");
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ الجداول المناسبة:\n\n";
    
    foreach ($suitableTables as $index => $table) {
        echo "   " . ($index + 1) . ". " . $table['name'] . "\n";
        echo "      الأعمدة المحتملة: " . implode(', ', $table['potential_phone_columns']) . "\n";
        
        // عرض عينة
        if (!empty($table['sample'])) {
            echo "      نموذج من البيانات:\n";
            foreach ($table['sample'][0] as $key => $value) {
                $displayValue = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
                echo "         $key: $displayValue\n";
            }
        }
        echo "\n";
    }
    
    echo "❓ حدد رقم الجدول: ";
    $tableIndex = trim(fgets(STDIN));
    $tableIndex = intval($tableIndex) - 1;
    
    if (!isset($suitableTables[$tableIndex])) {
        die("❌ اختيار غير صحيح\n");
    }
    
    $selectedTable = $suitableTables[$tableIndex];
    $tableName = $selectedTable['name'];
    
    echo "\n✅ تم اختيار الجدول: $tableName\n\n";
    
    // اختيار العمود
    echo "❓ الأعمدة المتاحة:\n";
    foreach ($selectedTable['columns'] as $index => $col) {
        echo "   " . ($index + 1) . ". $col\n";
    }
    
    echo "\n❓ حدد رقم العمود الذي يحتوي على الأرقام: ";
    $colIndex = trim(fgets(STDIN));
    $colIndex = intval($colIndex) - 1;
    
    if (!isset($selectedTable['columns'][$colIndex])) {
        die("❌ اختيار غير صحيح\n");
    }
    
    $phoneColumn = $selectedTable['columns'][$colIndex];
    
    echo "\n✅ تم اختيار العمود: $phoneColumn\n\n";
    
    // قراءة جميع البيانات
    echo "📥 جاري قراءة جميع البيانات من $tableName...\n";
    $stmt = $pdo->query("SELECT [$phoneColumn] FROM [$tableName]");
    
    $phones = [];
    $totalRows = 0;
    $invalidCount = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $totalRows++;
        
        $phone = $row[$phoneColumn];
        
        // تنظيف الرقم
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // إضافة 0 في البداية إذا لزم الأمر
        if (strlen($cleanPhone) === 9 && !$cleanPhone[0] === '0') {
            $cleanPhone = '0' . $cleanPhone;
        }
        
        // التحقق من صحة الرقم
        if (strlen($cleanPhone) >= 9 && strlen($cleanPhone) <= 12) {
            if (!in_array($cleanPhone, $phones)) {
                $phones[] = $cleanPhone;
            }
        } else {
            $invalidCount++;
        }
        
        if ($totalRows % 1000 == 0) {
            echo "   تم معالجة $totalRows صف...\n";
        }
    }
    
    echo "✅ تم معالجة $totalRows صف\n";
    echo "✅ تم استخراج " . count($phones) . " رقم فريد\n";
    echo "⚠️  تم تجاهل $invalidCount رقم غير صالح\n\n";
    
    if (empty($phones)) {
        die("❌ لم يتم استخراج أي أرقام صالحة\n");
    }
    
    echo "❓ هل تريد نقل الأرقام إلى قاعدة البيانات؟ (y/n): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) !== 'y') {
        die("تم الإلغاء\n");
    }
    
    // نقل الأرقام إلى MySQL
    echo "\n📤 جاري نقل الأرقام إلى قاعدة البيانات...\n";
    
    $addedCount = 0;
    $skippedCount = 0;
    
    foreach ($phones as $index => $phone) {
        // التحقق من عدم وجود الرقم مسبقاً
        if (!ExtractedPhone::where('phone', $phone)->exists()) {
            ExtractedPhone::create([
                'phone' => $phone,
                'image_name' => 'imported_from_zain_' . $tableName,
                'status' => 0, // Unassigned
                'whatsapp_status' => null, // لم يتم التحقق بعد
            ]);
            $addedCount++;
            
            if ($addedCount % 100 == 0) {
                echo "   تم إضافة $addedCount رقم...\n";
            }
        } else {
            $skippedCount++;
        }
    }
    
    echo "\n✅ تم الانتهاء من الاستيراد!\n";
    echo "   ➕ تم إضافة: $addedCount رقم جديد\n";
    echo "   ⏭️  تم تخطي: $skippedCount رقم (موجود مسبقاً)\n";
    echo "   📊 المجموع في قاعدة البيانات: " . ExtractedPhone::count() . "\n\n";
    
    echo "❓ هل تريد بدء التحقق من الأرقام على واتساب الآن؟ (y/n): ";
    $startCheck = trim(fgets(STDIN));
    
    if (strtolower($startCheck) === 'y') {
        echo "\n🚀 جاري بدء عملية التحقق...\n";
        
        $uncheckedPhones = ExtractedPhone::whereNull('whatsapp_status')
            ->where('image_name', 'like', 'imported_from_zain_%')
            ->get();
        
        if ($uncheckedPhones->isEmpty()) {
            echo "⚠️  جميع الأرقام تم التحقق منها مسبقاً\n";
            exit;
        }
        
        echo "📱 عدد الأرقام التي ستتحقق: " . $uncheckedPhones->count() . "\n";
        
        echo "\n❓ كم ثانية تريد بين كل رقم؟ (افتراضي: 5): ";
        $delay = trim(fgets(STDIN));
        $delay = $delay ? intval($delay) : 5;
        
        echo "🔄 جاري إرسال الأرقام إلى الـ Queue...\n";
        
        foreach ($uncheckedPhones as $index => $phoneRecord) {
            CheckWhatsAppNumber::dispatch($phoneRecord->phone, $phoneRecord->id)
                ->delay(now()->addSeconds($index * $delay));
            
            if (($index + 1) % 100 == 0) {
                echo "   تم إرسال " . ($index + 1) . " رقم...\n";
            }
        }
        
        $estimatedTime = round(($uncheckedPhones->count() * $delay) / 60, 2);
        
        echo "\n✅ تم إرسال جميع الأرقام إلى الـ Queue!\n";
        echo "⏱️  الوقت المتوقع: ~$estimatedTime دقيقة\n";
        echo "\n💡 افتح في المتصفح:\n";
        echo "   http://localhost/cms-ai/public/whatsapp-checker\n\n";
        echo "⚠️  تأكد من تشغيل: php artisan queue:work\n\n";
    }
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Driver') !== false) {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "❌ لم يتم العثور على Microsoft Access Driver\n\n";
        echo "🔧 الحل:\n";
        echo "   1. قم بتثبيت: https://www.microsoft.com/download/details.aspx?id=54920\n";
        echo "   2. أو استخدم الواجهة البديلة:\n";
        echo "      http://localhost/cms-ai/public/import-numbers\n\n";
        echo "📋 الخطوات البديلة:\n";
        echo "   1. افتح Zain.accdb في Access\n";
        echo "   2. تصدير إلى CSV\n";
        echo "   3. استخدم الواجهة الالكترونية\n";
    } else {
        echo "❌ خطأ: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

