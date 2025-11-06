<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ExtractedPhone;
use App\Models\DataCv;
use App\Jobs\CheckWhatsAppNumber;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * رفع ومعالجة ملف CSV على الخادم
     */
    public function uploadAndProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:204800', // 200MB - يدعم Excel
        ]);
        
        // زيادة المدة المسموح بها
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '0');
        ini_set('post_max_size', '210M');
        ini_set('upload_max_filesize', '210M');

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filePath = $file->storeAs('temp_imports', 'import_' . time() . '.' . $extension);
        $fullPath = storage_path('app/' . $filePath);

        try {
            // قراءة الملف حسب نوعه
            if (in_array(strtolower($extension), ['xlsx', 'xls'])) {
                // قراءة ملف Excel
                $result = $this->parseExcelFile($fullPath);
            } else {
                // قراءة ملف CSV
                $result = $this->parseLargeCSV($fullPath);
            }
            
            // حذف الملف المؤقت
            Storage::delete($filePath);

            return response()->json([
                'success' => true,
                'total_records' => $result['total_rows'] ?? count($result['data'] ?? []),
                'displayed_records' => $result['displayed_rows'] ?? 0,
                'has_more' => $result['has_more'] ?? false,
                'sample' => array_slice($result['data'] ?? [], 0, 10),
                'columns' => $this->detectColumns($result['data'] ?? []),
                'data' => $result['data'] ?? []
            ]);
        } catch (\Exception $e) {
            // حذف الملف المؤقت في حالة الخطأ
            Storage::delete($filePath);
            Log::error('Upload process error: ' . $e->getMessage());
            Log::error('Stack: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في معالجة الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقسيم الملف إلى أجزاء صغيرة
     */
    public function splitFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt,xlsx,xls|max:204800',
                'parts' => 'nullable|integer|min:2|max:20',
            ]);
            
            set_time_limit(300);
            ini_set('memory_limit', '512M');
            
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $parts = $request->input('parts', 10); // افتراضي 10 أجزاء
            
            $filePath = $file->storeAs('temp_imports', 'split_' . time() . '.' . $extension);
            $fullPath = storage_path('app/' . $filePath);
            
            Log::info('Splitting file into ' . $parts . ' parts');
            
            try {
                // قراءة الملف
                if (in_array($extension, ['xlsx', 'xls'])) {
                    Log::info('Reading Excel file...');
                    $data = $this->parseExcelForDataCv($fullPath);
                    Log::info('Excel file read, rows: ' . count($data));
                } else {
                    Log::info('Reading CSV file...');
                    $data = $this->parseCSVForDataCv($fullPath);
                    Log::info('CSV file read, rows: ' . count($data));
                }
            } catch (\Exception $e) {
                Storage::delete($filePath);
                Log::error('Error reading file: ' . $e->getMessage());
                Log::error('Stack: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في قراءة الملف: ' . $e->getMessage()
                ], 500);
            }
            
            Storage::delete($filePath);
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على بيانات في الملف'
                ], 400);
            }
            
            Log::info('Starting to split ' . count($data) . ' rows into ' . $parts . ' parts');
            
            // تقسيم البيانات
            $totalRows = count($data);
            $rowsPerPart = ceil($totalRows / $parts);
            $chunks = array_chunk($data, $rowsPerPart);
            
            // حفظ كل جزء في ملف منفصل
            $splitFiles = [];
            $splitDir = storage_path('app/temp_splits');
            if (!is_dir($splitDir)) {
                mkdir($splitDir, 0755, true);
            }
            
            $timestamp = time();
            foreach ($chunks as $index => $chunk) {
                try {
                    $partFileName = 'part_' . ($index + 1) . '_' . $timestamp . '.csv';
                    $partFilePath = $splitDir . '/' . $partFileName;
                    
                    $handle = fopen($partFilePath, 'w');
                    if (!$handle) {
                        Log::error('Cannot create file: ' . $partFilePath);
                        continue;
                    }
                    
                    // كتابة الرؤوس
                    fputcsv($handle, ['phone_number', 'name', 'address']);
                    
                    // كتابة البيانات
                    foreach ($chunk as $row) {
                        fputcsv($handle, [
                            $row['phone_number'] ?? '',
                            $row['name'] ?? '',
                            $row['address'] ?? ''
                        ]);
                    }
                    fclose($handle);
                    
                    $splitFiles[] = [
                        'part' => $index + 1,
                        'filename' => $partFileName,
                        'rows' => count($chunk),
                        'path' => 'temp_splits/' . $partFileName
                    ];
                    
                    Log::info('Created part ' . ($index + 1) . ' with ' . count($chunk) . ' rows');
                } catch (\Exception $e) {
                    Log::error('Error creating part ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }
            
            if (empty($splitFiles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إنشاء الأجزاء'
                ], 500);
            }
            
            Log::info('File split successfully into ' . count($splitFiles) . ' parts');
            
            return response()->json([
                'success' => true,
                'message' => 'تم تقسيم الملف بنجاح',
                'total_rows' => $totalRows,
                'parts' => count($splitFiles),
                'files' => $splitFiles
            ]);
            
        } catch (\Exception $e) {
            Log::error('Split file error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // التأكد من إرجاع JSON صالح
            try {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage(),
                    'error_details' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ] : null
                ], 500);
            } catch (\Exception $jsonError) {
                // في حالة فشل JSON، نعيد نص عادي
                Log::error('JSON encoding error: ' . $jsonError->getMessage());
                return response('خطأ في معالجة الملف: ' . $e->getMessage(), 500)
                    ->header('Content-Type', 'text/plain');
            }
        }
    }
    
    /**
     * استيراد جزء واحد من الملف المقسم
     */
    public function importSplitPart(Request $request)
    {
        try {
            $request->validate([
                'filename' => 'required|string',
            ]);
            
            set_time_limit(120);
            ini_set('memory_limit', '256M');
            
            $filename = $request->input('filename');
            $filePath = storage_path('app/temp_splits/' . $filename);
            
            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود'
                ], 404);
            }
            
            // قراءة ملف CSV المقسم
            $data = [];
            $handle = fopen($filePath, 'r');
            
            // تخطي السطر الأول (الرؤوس)
            fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                if (empty(array_filter($row))) continue;
                
                $data[] = [
                    'phone_number' => trim($row[0] ?? ''),
                    'name' => trim($row[1] ?? ''),
                    'address' => trim($row[2] ?? ''),
                ];
            }
            fclose($handle);
            
            // إدخال البيانات
            $imported = 0;
            $errors = 0;
            
            if (!empty($data)) {
                $chunks = array_chunk($data, 500);
                foreach ($chunks as $chunk) {
                    $insertData = [];
                    foreach ($chunk as $row) {
                        $insertData[] = [
                            'phone_number' => $row['phone_number'],
                            'name' => $row['name'],
                            'address' => $row['address'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    
                    try {
                        DB::table('data_cv')->insert($insertData);
                        $imported += count($insertData);
                    } catch (\Exception $e) {
                        $errors += count($insertData);
                        Log::error('Insert error: ' . $e->getMessage());
                    }
                }
            }
            
            // حذف الملف بعد الاستيراد
            @unlink($filePath);
            
            return response()->json([
                'success' => true,
                'message' => 'تم استيراد الجزء بنجاح',
                'imported' => $imported,
                'errors' => $errors,
                'total' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import split part error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض وإدارة بيانات data_cv مع pagination
     */
    public function manageDataCv(Request $request)
    {
        // إذا كان POST = استيراد ملف
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->importToDataCvSimple($request);
        }
        
        // إذا كان GET = عرض البيانات
        $query = DataCv::query();
        
        // استبعاد السجلات المنقولة تلقائياً (whatsapp_status = 3)
        $query->where(function($q) {
            $q->where(function($subQ) {
                $subQ->where('whatsapp_status', '!=', 3)
                     ->orWhereNull('whatsapp_status');
            });
        });
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('phone_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب حالة الواتساب
        if ($request->has('whatsapp_status')) {
            $status = $request->whatsapp_status;
            if ($status === '0') {
                $query->whereNull('whatsapp_status');
            } elseif ($status === '1') {
                $query->where('whatsapp_status', 1);
            } elseif ($status === '2') {
                $query->where('whatsapp_status', 0);
            }
            // تم إزالة حالة 3 (منقول) من الفلترة لأنها مستبعدة تلقائياً
        }
        
        // الإحصائيات
        $stats = [
            'total' => DataCv::count(),
            'on_whatsapp' => DataCv::where('whatsapp_status', 1)->count(),
            'not_on_whatsapp' => DataCv::where('whatsapp_status', 0)->count(),
            'moved' => DataCv::where('whatsapp_status', 3)->count(),
        ];
        
        // Pagination
        $data = $query->orderBy('id', 'desc')->paginate(500);
        
        return view('data-cv-manage', compact('data', 'stats'));
    }

    /**
     * حذف سجل واحد من data_cv
     */
    public function deleteDataCv(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:data_cv,id'
        ]);
        
        try {
            $dataCv = DataCv::find($request->id);
            if (!$dataCv) {
                return response()->json([
                    'success' => false,
                    'message' => 'السجل غير موجود'
                ], 404);
            }
            
            $dataCv->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم الحذف بنجاح'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete data_cv error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف عدة سجلات من data_cv
     */
    public function deleteDataCvBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:data_cv,id'
        ]);
        
        try {
            $ids = $request->ids;
            $deleted = DataCv::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "تم حذف {$deleted} سجل بنجاح",
                'deleted' => $deleted
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete data_cv batch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض الأرقام المنقولة إلى extracted_phones
     */
    public function showMovedData(Request $request)
    {
        // جلب الأرقام المنقولة من extracted_phones
        $query = \App\Models\ExtractedPhone::query();
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }
        
        // الإحصائيات للأرقام المنقولة
        $stats = [
            'total_moved' => \App\Models\ExtractedPhone::count(),
            'with_whatsapp' => \App\Models\ExtractedPhone::where('whatsapp_status', 1)->count(),
            'without_whatsapp' => \App\Models\ExtractedPhone::where('whatsapp_status', 0)->count(),
        ];
        
        // Pagination
        $data = $query->orderBy('id', 'desc')->paginate(500);
        
        return view('data-cv-manage', [
            'data' => $data,
            'stats' => $stats,
            'is_moved_page' => true
        ]);
    }

    /**
     * استيراد مباشر عبر POST عادي (بدون API)
     */
    public function importToDataCvSimple(Request $request)
    {
        // زيادة الوقت والذاكرة
        set_time_limit(600);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt,xlsx,xls|max:204800',
            ]);
            
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('temp_imports', 'datacv_' . time() . '.' . $extension);
            $fullPath = storage_path('app/' . $filePath);
            
            Log::info('Starting simple import, file: ' . $file->getClientOriginalName());
            
            // قراءة الملف
            if (in_array($extension, ['xlsx', 'xls'])) {
                Log::info('Reading Excel file...');
                $data = $this->parseExcelForDataCv($fullPath);
            } else {
                Log::info('Reading CSV file...');
                $data = $this->parseCSVForDataCv($fullPath);
            }
            
            Log::info('File parsed, rows: ' . count($data));
            
            // حذف الملف المؤقت
            Storage::delete($filePath);
            
            if (empty($data)) {
                return redirect()->back()->with('error', 'لم يتم العثور على بيانات في الملف');
            }
            
            // إدخال البيانات على دفعات
            $imported = 0;
            $errors = 0;
            $batchSize = 500;
            
            foreach (array_chunk($data, $batchSize) as $batch) {
                $insertData = [];
                foreach ($batch as $row) {
                    $insertData[] = [
                        'phone_number' => $row['phone_number'] ?? '',
                        'name' => $row['name'] ?? '',
                        'address' => $row['address'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                try {
                    DB::table('data_cv')->insert($insertData);
                    $imported += count($insertData);
                    Log::info('Imported batch: ' . $imported . ' rows');
                } catch (\Exception $e) {
                    $errors += count($insertData);
                    Log::error('Batch insert error: ' . $e->getMessage());
                    
                    // محاولة إدخال واحد تلو الآخر
                    foreach ($insertData as $row) {
                        try {
                            DB::table('data_cv')->insert([$row]);
                            $imported++;
                            $errors--;
                        } catch (\Exception $e2) {
                            Log::error('Single insert error: ' . $e2->getMessage());
                        }
                    }
                }
            }
            
            Log::info('Import completed: ' . $imported . ' imported, ' . $errors . ' errors');
            
            return redirect()->back()->with([
                'success' => true,
                'imported' => $imported,
                'errors' => $errors,
                'total' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import to data_cv error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من رقم واحد في data_cv
     */
    public function checkWhatsAppDataCv(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:data_cv,id'
        ]);
        
        try {
            $dataCv = DataCv::find($request->id);
            if (!$dataCv) {
                return response()->json([
                    'success' => false,
                    'message' => 'السجل غير موجود'
                ], 404);
            }
            
            // إرسال Job للتحقق
            CheckWhatsAppNumber::dispatch($dataCv->phone_number, $dataCv->id, 'data_cv')
                ->delay(now()->addSeconds(1));
            
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلب التحقق'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Check WhatsApp error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * التحقق من عدة أرقام في data_cv
     */
    public function checkWhatsAppDataCvBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:data_cv,id',
            'delay_seconds' => 'nullable|integer|min:1|max:30'
        ]);
        
        try {
            $ids = $request->ids;
            $delay = $request->delay_seconds ?? 10; // تغيير القيمة الافتراضية من 3 إلى 10 ثواني
            $processed = 0;
            
            foreach ($ids as $index => $id) {
                $dataCv = DataCv::find($id);
                if ($dataCv) {
                    // التأخير: 10 ثواني بين كل رقم
                    CheckWhatsAppNumber::dispatch($dataCv->phone_number, $dataCv->id, 'data_cv')
                        ->delay(now()->addSeconds($index * $delay));
                    $processed++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "تم إرسال {$processed} رقم للتحقق",
                'processed' => $processed
            ]);
            
        } catch (\Exception $e) {
            Log::error('Check WhatsApp batch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * نقل سجل واحد من data_cv إلى extracted_phones
     */
    public function moveDataCvToExtracted(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:data_cv,id'
        ]);
        
        try {
            $dataCv = DataCv::find($request->id);
            if (!$dataCv) {
                return response()->json([
                    'success' => false,
                    'message' => 'السجل غير موجود'
                ], 404);
            }
            
            // التحقق من وجود الرقم في extracted_phones
            $exists = ExtractedPhone::where('phone', $dataCv->phone_number)->first();
            
            if ($exists) {
                // تحديث السجل الموجود
                $exists->update([
                    'name' => $dataCv->name,
                    'note' => $dataCv->address,
                    'whatsapp_status' => 1
                ]);
            } else {
                // إنشاء سجل جديد
                ExtractedPhone::create([
                    'phone' => $dataCv->phone_number,
                    'name' => $dataCv->name,
                    'note' => $dataCv->address,
                    'image_name' => 'moved_from_data_cv',
                    'status' => 0,
                    'whatsapp_status' => 1,
                ]);
            }
            
            // تحديث حالة data_cv إلى "منقول"
            $dataCv->update([
                'whatsapp_status' => 3, // منقول
                'whatsapp_checked_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم النقل بنجاح'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Move to extracted error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * نقل عدة سجلات من data_cv إلى extracted_phones (الأرقام الموجودة على واتساب فقط)
     */
    public function moveDataCvToExtractedBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:data_cv,id'
        ]);
        
        try {
            $ids = $request->ids;
            $moved = 0;
            $skipped = 0;
            
            foreach ($ids as $id) {
                $dataCv = DataCv::find($id);
                if (!$dataCv) continue;
                
                // نقل فقط الأرقام الموجودة على واتساب
                if ($dataCv->whatsapp_status != 1) {
                    $skipped++;
                    continue;
                }
                
                // التحقق من وجود الرقم
                $exists = ExtractedPhone::where('phone', $dataCv->phone_number)->first();
                
                if ($exists) {
                    $exists->update([
                        'name' => $dataCv->name,
                        'note' => $dataCv->address,
                        'whatsapp_status' => 1
                    ]);
                } else {
                    ExtractedPhone::create([
                        'phone' => $dataCv->phone_number,
                        'name' => $dataCv->name,
                        'note' => $dataCv->address,
                        'image_name' => 'moved_from_data_cv',
                        'status' => 0,
                        'whatsapp_status' => 1,
                    ]);
                }
                
                // تحديث حالة data_cv
                $dataCv->update([
                    'whatsapp_status' => 3,
                    'whatsapp_checked_at' => now()
                ]);
                
                $moved++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "تم نقل {$moved} سجل",
                'moved' => $moved,
                'skipped' => $skipped
            ]);
            
        } catch (\Exception $e) {
            Log::error('Move batch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * استيراد مباشر إلى جدول data_cv بدون فلترة
     */
    public function importToDataCv(Request $request)
    {
        // زيادة الوقت والذاكرة
        set_time_limit(600); // 10 دقائق
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt,xlsx,xls|max:204800',
            ]);
            
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('temp_imports', 'datacv_' . time() . '.' . $extension);
            $fullPath = storage_path('app/' . $filePath);
            
            Log::info('Starting import, file: ' . $file->getClientOriginalName());
            
            // قراءة الملف
            if (in_array($extension, ['xlsx', 'xls'])) {
                Log::info('Reading Excel file...');
                $data = $this->parseExcelForDataCv($fullPath);
            } else {
                Log::info('Reading CSV file...');
                $data = $this->parseCSVForDataCv($fullPath);
            }
            
            Log::info('File parsed, rows: ' . count($data));
            
            // حذف الملف المؤقت
            Storage::delete($filePath);
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على بيانات في الملف'
                ], 400);
            }
            
            // إعداد البيانات للإدخال على دفعات صغيرة لتجنب timeout
            $imported = 0;
            $errors = 0;
            $batchSize = 500; // دفعات أصغر
            
            foreach (array_chunk($data, $batchSize) as $batch) {
                $insertData = [];
                foreach ($batch as $row) {
                    $insertData[] = [
                        'phone_number' => $row['phone_number'] ?? '',
                        'name' => $row['name'] ?? '',
                        'address' => $row['address'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                try {
                    DB::table('data_cv')->insert($insertData);
                    $imported += count($insertData);
                    Log::info('Imported batch: ' . $imported . ' rows');
                } catch (\Exception $e) {
                    $errors += count($insertData);
                    Log::error('Batch insert error: ' . $e->getMessage());
                    
                    // محاولة إدخال واحد تلو الآخر للدفعة الفاشلة
                    foreach ($insertData as $row) {
                        try {
                            DB::table('data_cv')->insert([$row]);
                            $imported++;
                            $errors--;
                        } catch (\Exception $e2) {
                            Log::error('Single insert error: ' . $e2->getMessage());
                        }
                    }
                }
            }
            
            Log::info('Import completed: ' . $imported . ' imported, ' . $errors . ' errors');
            
            return response()->json([
                'success' => true,
                'message' => 'تم الاستيراد بنجاح',
                'imported' => $imported,
                'errors' => $errors,
                'total' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import to data_cv error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    
    /**
     * قراءة Excel للإدخال المباشر في data_cv (مبسط وسريع)
     */
    private function parseExcelForDataCv($filePath)
    {
        $data = [];
        
        try {
            // استخدام Xlsx reader مباشرة - أسرع
            $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePath);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            
            // قراءة فقط البيانات المطلوبة
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $highestRow = $worksheet->getHighestDataRow(); // فقط الصفوف التي تحتوي على بيانات
            $highestColumn = $worksheet->getHighestDataColumn();
            
            // قراءة السطر الأول (الرؤوس) فقط
            $headerRow = $worksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, false)[0];
            $headers = array_map('trim', $headerRow);
            $phoneIndex = $this->findColumnIndex($headers, ['phone', 'number', 'mobile', 'رقم', 'phone_number']);
            $nameIndex = $this->findColumnIndex($headers, ['name', 'اسم', 'الاسم']);
            $addressIndex = $this->findColumnIndex($headers, ['living', 'address', 'location', 'عنوان', 'العنوان']);
            
            // إذا لم يتم العثور على الرؤوس، استخدم المواضع الثابتة
            if ($phoneIndex === null) {
                $phoneIndex = 0;
                $nameIndex = 1;
                $addressIndex = 2;
                Log::warning('Headers not found, using fixed positions: phone=0, name=1, address=2');
            }
            
            Log::info('Headers found - Phone: ' . ($phoneIndex ?? 'null') . ', Name: ' . ($nameIndex ?? 'null') . ', Address: ' . ($addressIndex ?? 'null'));
            Log::info('Headers: ' . json_encode($headers));
            
            // قراءة باقي الصفوف على دفعات
            $batchSize = 1000;
            for ($startRow = 2; $startRow <= $highestRow; $startRow += $batchSize) {
                $endRow = min($startRow + $batchSize - 1, $highestRow);
                $range = 'A' . $startRow . ':' . $highestColumn . $endRow;
                $batchData = $worksheet->rangeToArray($range, null, true, false);
                
                foreach ($batchData as $rowIndex => $rowData) {
                    if (empty(array_filter($rowData))) {
                        continue;
                    }
                    
                    // التحقق من أن الصف يحتوي على بيانات كافية
                    if (count($rowData) < 3) {
                        continue;
                    }
                    
                    // استخراج البيانات
                    $phone = isset($rowData[$phoneIndex]) ? trim($rowData[$phoneIndex]) : trim($rowData[0] ?? '');
                    $name = isset($rowData[$nameIndex]) ? trim($rowData[$nameIndex]) : trim($rowData[1] ?? '');
                    $address = isset($rowData[$addressIndex]) ? trim($rowData[$addressIndex]) : trim($rowData[2] ?? '');
                    
                    // التحقق من أن جميع الحقول ليست نفس القيمة (مشكلة في الفهارس)
                    if ($phone === $name && $phone === $address && !empty($phone) && strlen($phone) > 5) {
                        Log::warning("Row " . ($startRow + $rowIndex) . ": All fields same, using fixed positions");
                        $phone = trim($rowData[0] ?? '');
                        $name = trim($rowData[1] ?? '');
                        $address = trim($rowData[2] ?? '');
                    }
                    
                    if (!empty($phone)) {
                        $data[] = [
                            'phone_number' => $phone,
                            'name' => $name,
                            'address' => $address,
                        ];
                    }
                }
                
                // تحرير الذاكرة
                if ($startRow % 5000 === 0) {
                    gc_collect_cycles();
                }
            }
            
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $worksheet);
            gc_collect_cycles();
            
            Log::info('Excel parsed successfully: ' . count($data) . ' rows');
            
        } catch (\Exception $e) {
            Log::error('Excel parse error: ' . $e->getMessage());
            Log::error('Stack: ' . $e->getTraceAsString());
            throw $e;
        }
        
        return $data;
    }
    
    /**
     * قراءة CSV للإدخال المباشر في data_cv
     */
    private function parseCSVForDataCv($filePath)
    {
        $data = [];
        
        // قراءة أول سطرين لتحديد التنسيق
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            Log::error('Cannot open file: ' . $filePath);
            return [];
        }
        
        // قراءة أول سطر لتحديد الفاصل (Tab, Comma, etc.)
        $firstLine = fgets($handle);
        rewind($handle);
        
        // تحديد الفاصل
        $delimiter = $this->detectDelimiter($firstLine);
        Log::info('Detected delimiter: ' . ($delimiter === "\t" ? 'TAB' : ($delimiter === ',' ? 'COMMA' : 'OTHER')));
        
        // قراءة الرؤوس
        $headers = fgetcsv($handle, 0, $delimiter);
        if (!$headers || empty(array_filter($headers))) {
            Log::warning('No headers found, using fixed positions');
            rewind($handle);
            $headers = null;
        } else {
            $headers = array_map('trim', $headers);
            Log::info('Headers: ' . json_encode($headers));
        }
        
        // تحديد فهارس الأعمدة
        if ($headers) {
            $phoneIndex = $this->findColumnIndex($headers, ['phone', 'number', 'mobile', 'رقم', 'phone_number']);
            $nameIndex = $this->findColumnIndex($headers, ['name', 'اسم', 'الاسم', 'name']);
            $addressIndex = $this->findColumnIndex($headers, ['living', 'address', 'location', 'عنوان', 'العنوان']);
        } else {
            // إذا لم توجد رؤوس، نستخدم المواضع الثابتة (0, 1, 2)
            $phoneIndex = 0;
            $nameIndex = 1;
            $addressIndex = 2;
            Log::info('Using fixed positions: phone=0, name=1, address=2');
        }
        
        $rowNum = 0;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNum++;
            
            // تخطي الصفوف الفارغة
            if (empty(array_filter($row))) {
                continue;
            }
            
            // التحقق من أن الصف يحتوي على بيانات كافية
            if (count($row) < 3) {
                Log::warning("Row {$rowNum} has less than 3 columns, skipping");
                continue;
            }
            
            // استخراج البيانات مع استخدام الفهارس الصحيحة
            $phone = isset($phoneIndex) && isset($row[$phoneIndex]) ? trim($row[$phoneIndex]) : trim($row[0] ?? '');
            $name = isset($nameIndex) && isset($row[$nameIndex]) ? trim($row[$nameIndex]) : trim($row[1] ?? '');
            $address = isset($addressIndex) && isset($row[$addressIndex]) ? trim($row[$addressIndex]) : trim($row[2] ?? '');
            
            // التأكد من أن الرقم موجود
            if (!empty($phone)) {
                // إذا كانت جميع الحقول تحتوي على نفس القيمة (الرقم)، يعني خطأ في الفهارس
                if ($phone === $name && $phone === $address && !empty($name)) {
                    Log::warning("Row {$rowNum}: All fields have same value, using fixed positions");
                    $phone = trim($row[0] ?? '');
                    $name = trim($row[1] ?? '');
                    $address = trim($row[2] ?? '');
                }
                
                $data[] = [
                    'phone_number' => $phone,
                    'name' => $name,
                    'address' => $address,
                ];
            } else {
                Log::warning("Row {$rowNum}: Empty phone number, skipping");
            }
        }
        
        fclose($handle);
        
        Log::info('Parsed CSV: ' . count($data) . ' rows');
        return $data;
    }
    
    /**
     * تحديد فاصل الملف (Tab, Comma, etc.)
     */
    private function detectDelimiter($firstLine)
    {
        $delimiters = ["\t", ',', ';', '|'];
        $maxCount = 0;
        $bestDelimiter = ',';
        
        foreach ($delimiters as $delimiter) {
            $count = substr_count($firstLine, $delimiter);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delimiter;
            }
        }
        
        return $bestDelimiter;
    }

    /**
     * فلترة البيانات حسب العنوان - معالجة كاملة في الباكند
     */
    public function filterData(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required|array',
                'filter' => 'nullable|string',
                'filter_method' => 'nullable|string'
            ]);

            $data = $request->data;
            $filter = strtolower(trim($request->filter ?? ''));
            $method = $request->filter_method ?? 'contains';

            // إذا لم يكن هناك فلتر، ارجع كل البيانات
            if (empty($filter)) {
                return response()->json([
                    'success' => true,
                    'filtered_data' => $data,
                    'count' => count($data)
                ]);
            }

            $filtered = [];
            $processed = 0;
            
            foreach ($data as $row) {
                // البحث في العنوان والاسم
                $address = strtolower($row['address'] ?? '');
                $name = strtolower($row['name'] ?? '');
                
                $matches = false;
                
                // استخدام strpos للتوافق مع PHP 7
                if ($method === 'contains') {
                    $matches = (strpos($address, $filter) !== false) || (strpos($name, $filter) !== false);
                } elseif ($method === 'start') {
                    $matches = (strpos($address, $filter) === 0) || (strpos($name, $filter) === 0);
                } elseif ($method === 'end') {
                    $len = strlen($filter);
                    $matches = (substr($address, -$len) === $filter) || (substr($name, -$len) === $filter);
                } else {
                    $matches = (strpos($address, $filter) !== false) || (strpos($name, $filter) !== false);
                }
                
                if ($matches) {
                    $filtered[] = $row;
                }
                
                $processed++;
            }

            return response()->json([
                'success' => true,
                'filtered_data' => $filtered,
                'count' => count($filtered),
                'total_processed' => $processed
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في الفلترة: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
                'filtered_data' => [],
                'count' => 0
            ], 500);
        }
    }
    
    /**
     * بدء عملية الاستيراد والتحقق من الواتساب مع تتبع التقدم
     */
    public function startImportWithProgress(Request $request)
    {
        $request->validate([
            'phones' => 'required|array',
            'delay_seconds' => 'nullable|integer|min:1|max:30'
        ]);

        // إنشاء معرّف للعملية
        $processId = uniqid('import_', true);
        $phones = $request->phones;
        $delaySeconds = $request->delay_seconds ?? 3;
        
        // حفظ معلومات العملية
        Cache::put("import_progress_{$processId}", [
            'total' => count($phones),
            'imported' => 0,
            'checked' => 0,
            'on_whatsapp' => 0,
            'not_on_whatsapp' => 0,
            'status' => 'processing'
        ], 3600);
        
        // بدء المعالجة في الخلفية
        dispatch(function() use ($phones, $delaySeconds, $processId) {
            $this->processImportBatch($phones, $delaySeconds, $processId);
        });
        
        return response()->json([
            'success' => true,
            'process_id' => $processId,
            'total' => count($phones),
            'message' => 'تم بدء عملية الاستيراد'
        ]);
    }
    
    /**
     * الحصول على حالة التقدم
     */
    public function getImportProgress(Request $request)
    {
        $processId = $request->input('process_id');
        
        if (!$processId) {
            return response()->json([
                'success' => false,
                'message' => 'معرّف العملية غير موجود'
            ]);
        }
        
        $progress = Cache::get("import_progress_{$processId}", [
            'total' => 0,
            'imported' => 0,
            'checked' => 0,
            'status' => 'not_found'
        ]);
        
        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }
    
    /**
     * معالجة دفعة الاستيراد
     */
    private function processImportBatch($phones, $delaySeconds, $processId)
    {
        $progress = Cache::get("import_progress_{$processId}");
        
        foreach ($phones as $index => $phoneObj) {
            $phone = $phoneObj['phone'] ?? '';
            
            if (empty($phone)) continue;
            
            // تنظيف الرقم
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            if (strlen($cleanPhone) === 12 && substr($cleanPhone, 0, 3) === '964') {
                $cleanPhone = '0' . substr($cleanPhone, 3);
            }
            
            if (strlen($cleanPhone) >= 9 && strlen($cleanPhone) <= 12) {
                if (!ExtractedPhone::where('phone', $cleanPhone)->exists()) {
                    $phoneRecord = ExtractedPhone::create([
                        'phone' => $cleanPhone,
                        'image_name' => $phoneObj['name'] ?? '',
                        'name' => $phoneObj['name'] ?? '',
                        'note' => $phoneObj['address'] ?? '',
                        'status' => 0,
                        'whatsapp_status' => null,
                    ]);
                    
                    $progress['imported']++;
                    
                    // إرسال للتحقق
                    CheckWhatsAppNumber::dispatch($phoneRecord->phone, $phoneRecord->id)
                        ->delay(now()->addSeconds($index * $delaySeconds));
                }
            }
            
            // تحديث التقدم كل 10 سجلات
            if ($index % 10 === 0) {
                $progress['total'] = count($phones);
                Cache::put("import_progress_{$processId}", $progress, 3600);
            }
        }
        
        $progress['status'] = 'completed';
        Cache::put("import_progress_{$processId}", $progress, 3600);
    }

    /**
     * استيراد الأرقام والتحقق منها (معالجة على دفعات)
     */
    public function importAndCheck(Request $request)
    {
        $request->validate([
            'phones' => 'required|array',
            'delay_seconds' => 'nullable|integer|min:1|max:30'
        ]);

        $phones = $request->phones;
        $delaySeconds = $request->delay_seconds ?? 3;
        
        set_time_limit(0); // لا محدود للوقت
        ini_set('memory_limit', '512M'); // زيادة الذاكرة
        
        $addedCount = 0;
        $existingCount = 0;
        $toCheckCount = 0;
        $batchSize = 500; // معالجة 500 رقم في كل مرة
        
        // معالجة على دفعات لتجنب استهلاك الذاكرة
        for ($i = 0; $i < count($phones); $i += $batchSize) {
            $batch = array_slice($phones, $i, $batchSize);
            
            foreach ($batch as $phoneObj) {
                $phone = $phoneObj['phone'] ?? '';
                
                if (empty($phone)) continue;
                
                // تنظيف الرقم
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                // تحويل من 964 إلى 0
                if (strlen($cleanPhone) === 12 && substr($cleanPhone, 0, 3) === '964') {
                    $cleanPhone = '0' . substr($cleanPhone, 3);
                }
                
                if (strlen($cleanPhone) >= 9 && strlen($cleanPhone) <= 12) {
                    if (!ExtractedPhone::where('phone', $cleanPhone)->exists()) {
                        $phoneRecord = ExtractedPhone::create([
                            'phone' => $cleanPhone,
                            'image_name' => $phoneObj['name'] ?? '',
                            'name' => $phoneObj['name'] ?? '',
                            'note' => $phoneObj['address'] ?? '',
                            'status' => 0,
                            'whatsapp_status' => null,
                        ]);
                        
                        $addedCount++;
                        
                        CheckWhatsAppNumber::dispatch($phoneRecord->phone, $phoneRecord->id)
                            ->delay(now()->addSeconds($toCheckCount * $delaySeconds));
                        
                        $toCheckCount++;
                    } else {
                        $existingCount++;
                    }
                }
            }
            
            // إراحة قصيرة بين الدفعات
            if (($i + $batchSize) < count($phones)) {
                usleep(50000); // 50ms
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'تم الاستيراد والتحقق بنجاح',
            'added' => $addedCount,
            'existing' => $existingCount,
            'to_check' => $toCheckCount,
            'total_processed' => count($phones)
        ]);
    }

    /**
     * قراءة ملف Excel
     */
    private function parseExcelFile($filePath)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $headers = [];
            $rowNum = 0;
            $batchSize = 0;
            $maxRows = 50000;
            $phoneIndex = 0;
            $nameIndex = 1;
            $addressIndex = 2;
            
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getFormattedValue();
                }
                
                // السطر الأول = الرؤوس
                if ($rowNum === 0) {
                    $headers = array_map('trim', $rowData);
                    $phoneIndex = $this->findColumnIndex($headers, ['phone', 'number', 'mobile', 'رقم']);
                    $nameIndex = $this->findColumnIndex($headers, ['name', 'اسم', 'الاسم']);
                    $addressIndex = $this->findColumnIndex($headers, ['living', 'address', 'location', 'عنوان', 'العنوان']);
                    $rowNum++;
                    continue;
                }
                
                // تخطي الصفوف الفارغة
                if (empty(array_filter($rowData))) {
                    continue;
                }
                
                $phone = trim($rowData[$phoneIndex] ?? '');
                if (!empty($phone)) {
                    if ($batchSize < $maxRows) {
                        $data[] = [
                            'phone' => $phone,
                            'name' => trim($rowData[$nameIndex] ?? ''),
                            'address' => trim($rowData[$addressIndex] ?? ''),
                            'row_index' => $rowNum
                        ];
                        $batchSize++;
                    }
                    $rowNum++;
                }
                
                // معالجة كل 10000 صف لتحرير الذاكرة
                if ($rowNum % 10000 === 0) {
                    gc_collect_cycles();
                }
            }
            
            return [
                'data' => $data,
                'total_rows' => $rowNum,
                'displayed_rows' => count($data),
                'has_more' => $rowNum > $maxRows
            ];
        } catch (\Exception $e) {
            Log::error('Error parsing Excel file: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return [
                'data' => [],
                'total_rows' => 0,
                'displayed_rows' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * قراءة ملف CSV كبير على دفعات (حصرياً)
     */
    private function parseLargeCSV($filePath)
    {
        set_time_limit(0); // لا محدود للوقت
        ini_set('memory_limit', '512M'); // زيادة الذاكرة
        
        $data = [];
        $handle = fopen($filePath, 'r');
        
        if ($handle === false) {
            return [];
        }

        // قراءة الرؤوس
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            return [];
        }
        
        // البحث عن فهارس الأعمدة
        $phoneIndex = $this->findColumnIndex($headers, ['phone', 'number', 'mobile', 'رقم']);
        $nameIndex = $this->findColumnIndex($headers, ['name', 'اسم', 'الاسم']);
        $addressIndex = $this->findColumnIndex($headers, ['living', 'address', 'location', 'عنوان', 'العنوان']);
        
        $rowNum = 0;
        $batchSize = 0;
        $maxRows = 50000; // حد أقصى للعرض
        
        // قراءة في دفعات
        while (($row = fgetcsv($handle)) !== false && $rowNum < 100000) {
            if (isset($row[$phoneIndex]) && !empty(trim($row[$phoneIndex]))) {
                $phone = trim($row[$phoneIndex]);
                
                // إضافة فقط أول 50000 صف للعرض
                if ($batchSize < $maxRows) {
                    $data[] = [
                        'phone' => $phone,
                        'name' => trim($row[$nameIndex] ?? ''),
                        'address' => trim($row[$addressIndex] ?? ''),
                        'row_index' => $rowNum
                    ];
                    $batchSize++;
                }
                
                $rowNum++;
            }
            
            // معالجة كل 10000 صف لتحرير الذاكرة
            if ($rowNum % 10000 === 0) {
                gc_collect_cycles();
            }
        }
        
        fclose($handle);
        
        // إضافة معلومات إضافية
        return [
            'data' => $data,
            'total_rows' => $rowNum,
            'displayed_rows' => count($data),
            'has_more' => $rowNum > $maxRows
        ];
    }

    /**
     * العثور على فهرس العمود
     */
    private function findColumnIndex($headers, $searchTerms)
    {
        if (empty($headers)) {
            return null;
        }
        
        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header));
            foreach ($searchTerms as $term) {
                if (str_contains($headerLower, strtolower($term))) {
                    Log::info("Found column '{$term}' at index {$index}");
                    return $index;
                }
            }
        }
        
        Log::warning("Column not found for terms: " . implode(', ', $searchTerms));
        return null;
    }

    /**
     * استخراج أسماء الأعمدة من الملف
     */
    public function getColumns(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $filePath = $file->storeAs('temp_imports', 'columns_' . time() . '.csv');
        $fullPath = storage_path('app/' . $filePath);

        $handle = fopen($fullPath, 'r');
        
        if ($handle === false) {
            return response()->json(['success' => false, 'message' => 'خطأ في قراءة الملف']);
        }

        // قراءة السطر الأول (الرؤوس)
        $headers = fgetcsv($handle);
        fclose($handle);

        // حذف الملف المؤقت
        Storage::delete($filePath);

        return response()->json([
            'success' => true,
            'columns' => $headers ?? []
        ]);
    }

    /**
     * اكتشاف الأعمدة في البيانات
     */
    private function detectColumns($data)
    {
        if (empty($data)) {
            return ['phone', 'name', 'address'];
        }
        
        $firstRow = $data[0];
        return array_keys($firstRow);
    }

    /**
     * معالجة الأرقام على دفعات لتجنب الاستهلاك الزائد
     */
    public function processInBatches($phones, $delaySeconds = 10)
    {
        $batchSize = 1000; // معالجة 1000 رقم في كل دفعة
        $totalBatches = ceil(count($phones) / $batchSize);
        
        for ($i = 0; $i < $totalBatches; $i++) {
            $batch = array_slice($phones, $i * $batchSize, $batchSize);
            
            foreach ($batch as $index => $phoneObj) {
                $phone = $phoneObj['phone'] ?? '';
                
                // تنظيف الرقم
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                // تحويل من 964 إلى 0
                if (strlen($cleanPhone) === 12 && substr($cleanPhone, 0, 3) === '964') {
                    $cleanPhone = '0' . substr($cleanPhone, 3);
                }
                
                if (strlen($cleanPhone) >= 9 && strlen($cleanPhone) <= 12) {
                    if (!ExtractedPhone::where('phone', $cleanPhone)->exists()) {
                        $phoneRecord = ExtractedPhone::create([
                            'phone' => $cleanPhone,
                            'image_name' => $phoneObj['name'] ?? '',
                            'name' => $phoneObj['name'] ?? '',
                            'note' => $phoneObj['address'] ?? '',
                            'status' => 0,
                            'whatsapp_status' => null,
                        ]);
                        
                        // إرسال للتحقق بتأخير
                        CheckWhatsAppNumber::dispatch($phoneRecord->phone, $phoneRecord->id)
                            ->delay(now()->addSeconds(($i * $batchSize + $index) * $delaySeconds));
                    }
                }
            }
            
            // إراحة بين الدفعات
            if ($i < $totalBatches - 1) {
                sleep(2);
            }
        }
    }
}

