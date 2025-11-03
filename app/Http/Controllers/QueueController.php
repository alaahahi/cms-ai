<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QueueController extends Controller
{
    /**
     * عرض صفحة إدارة Queue
     */
    public function index()
    {
        $stats = $this->getQueueStats();
        
        return view('queue-manage', compact('stats'));
    }

    /**
     * الحصول على إحصائيات Queue
     */
    public function getStats()
    {
        $stats = $this->getQueueStats();
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * جمع إحصائيات Queue
     */
    protected function getQueueStats()
    {
        // Jobs في الانتظار (كلها)
        $pendingJobs = DB::table('jobs')->count();
        
        // Jobs الفاشلة
        $failedJobs = DB::table('failed_jobs')->count();
        
        // آخر Job تمت معالجته
        $lastProcessed = DB::table('jobs')
            ->whereNotNull('reserved_at')
            ->orderBy('reserved_at', 'desc')
            ->first();
        
        // Jobs الجاهزة (لم يتم حجزها بعد وليس لديها delay)
        $now = time();
        $availableJobs = DB::table('jobs')
            ->whereNull('reserved_at')
            ->where(function($query) use ($now) {
                $query->whereNull('available_at')
                      ->orWhere('available_at', '<=', $now);
            })
            ->count();
        
        // Jobs مع delay (مؤجلة)
        $delayedJobs = DB::table('jobs')
            ->whereNull('reserved_at')
            ->whereNotNull('available_at')
            ->where('available_at', '>', $now)
            ->count();
        
        // Jobs قيد المعالجة (محجوزة)
        // Job محجوز إذا كان reserved_at موجود وليس منتهي الصلاحية (أقل من 90 ثانية)
        $processingJobs = DB::table('jobs')
            ->whereNotNull('reserved_at')
            ->where('reserved_at', '>', $now - 90) // 90 seconds = retry_after
            ->count();
        
        return [
            'pending' => $pendingJobs,
            'failed' => $failedJobs,
            'available' => $availableJobs,
            'delayed' => $delayedJobs,
            'processing' => $processingJobs,
            'last_processed_at' => $lastProcessed ? date('Y-m-d H:i:s', $lastProcessed->reserved_at) : null,
            'is_worker_running' => $this->checkWorkerStatus(),
            'queue_connection' => config('queue.default'),
        ];
    }

    /**
     * التحقق من حالة Worker
     */
    protected function checkWorkerStatus()
    {
        // محاولة التحقق من خلال Cache أو Process
        $lastHeartbeat = Cache::get('queue_worker_heartbeat');
        
        if ($lastHeartbeat) {
            // إذا كان آخر heartbeat منذ أقل من دقيقة، يعتبر Worker يعمل
            return (time() - $lastHeartbeat) < 60;
        }
        
        // طريقة بديلة: التحقق من وجود Jobs في المعالجة
        $processing = DB::table('jobs')
            ->whereNotNull('reserved_at')
            ->where('reserved_at', '>', time() - 120) // خلال آخر دقيقتين
            ->exists();
        
        return $processing;
    }

    /**
     * إعادة محاولة Jobs الفاشلة
     */
    public function retryFailedJobs(Request $request)
    {
        try {
            $jobId = $request->input('job_id');
            
            if ($jobId && $jobId !== 'all') {
                Artisan::call('queue:retry', ['id' => $jobId]);
            } else {
                // إعادة محاولة للكل
                $failedJobIds = DB::table('failed_jobs')->pluck('uuid')->toArray();
                foreach ($failedJobIds as $uuid) {
                    try {
                        Artisan::call('queue:retry', ['id' => $uuid]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to retry job: ' . $uuid . ' - ' . $e->getMessage());
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم إعادة المحاولة بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retry jobs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف Jobs الفاشلة
     */
    public function clearFailedJobs(Request $request)
    {
        try {
            $jobId = $request->input('job_id');
            
            if ($jobId && $jobId !== 'all') {
                try {
                    Artisan::call('queue:forget', ['id' => $jobId]);
                } catch (\Exception $e) {
                    // إذا فشل الأمر، نحذف مباشرة من قاعدة البيانات
                    DB::table('failed_jobs')->where('uuid', $jobId)->delete();
                }
            } else {
                DB::table('failed_jobs')->truncate();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف Jobs الفاشلة'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to clear failed jobs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض Jobs الفاشلة
     */
    public function getFailedJobs()
    {
        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(50)
            ->get();
        
        return response()->json([
            'success' => true,
            'jobs' => $failedJobs
        ]);
    }

    /**
     * عرض Jobs في الانتظار
     */
    public function getPendingJobs()
    {
        $pendingJobs = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($job) {
                $payload = json_decode($job->payload, true);
                
                // Extract available_at (delay time)
                $availableAt = $job->available_at ?? null;
                $availableAtFormatted = $availableAt ? date('Y-m-d H:i:s', $availableAt) : null;
                $isDelayed = $availableAt && $availableAt > time();
                $delaySeconds = $isDelayed ? ($availableAt - time()) : 0;
                
                // Extract job class name
                $jobClass = $payload['displayName'] ?? ($payload['job'] ?? 'Unknown');
                $jobClass = str_replace('Illuminate\\Queue\\CallQueuedHandler@call', '', $jobClass);
                
                // Try to extract job name from data
                if (isset($payload['data']['commandName'])) {
                    $jobClass = $payload['data']['commandName'];
                }
                
                return [
                    'id' => $job->id,
                    'queue' => $job->queue ?? 'default',
                    'payload' => $jobClass,
                    'created_at' => date('Y-m-d H:i:s', $job->created_at),
                    'available_at' => $availableAtFormatted,
                    'reserved_at' => $job->reserved_at ? date('Y-m-d H:i:s', $job->reserved_at) : null,
                    'is_delayed' => $isDelayed,
                    'delay_seconds' => $delaySeconds,
                    'status' => $job->reserved_at ? 'processing' : ($isDelayed ? 'delayed' : 'ready'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'jobs' => $pendingJobs,
            'queue_connection' => config('queue.default'),
            'message' => config('queue.default') === 'sync' 
                ? 'تحذير: Queue Connection هو sync. غير إلى database في ملف .env لرؤية Jobs' 
                : null
        ]);
    }

    /**
     * حذف Jobs من الانتظار
     */
    public function deletePendingJob(Request $request)
    {
        try {
            $jobId = $request->input('job_id');
            
            if ($jobId && $jobId !== 'all') {
                // حذف Job واحد
                $deleted = DB::table('jobs')->where('id', $jobId)->delete();
                
                if ($deleted) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تم حذف Job بنجاح'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Job غير موجود'
                    ], 404);
                }
            } else {
                // حذف كل Jobs في الانتظار (غير المحجوزة)
                $deleted = DB::table('jobs')
                    ->whereNull('reserved_at')
                    ->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => "تم حذف {$deleted} Job من الانتظار"
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete pending job: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}

