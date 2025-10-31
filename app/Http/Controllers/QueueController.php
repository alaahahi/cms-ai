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
        // Jobs في الانتظار
        $pendingJobs = DB::table('jobs')->count();
        
        // Jobs الفاشلة
        $failedJobs = DB::table('failed_jobs')->count();
        
        // آخر Job تمت معالجته
        $lastProcessed = DB::table('jobs')
            ->whereNotNull('reserved_at')
            ->orderBy('reserved_at', 'desc')
            ->first();
        
        // Jobs الجاهزة (لم يتم حجزها بعد)
        $availableJobs = DB::table('jobs')
            ->whereNull('reserved_at')
            ->count();
        
        // Jobs قيد المعالجة (محجوزة)
        $processingJobs = DB::table('jobs')
            ->whereNotNull('reserved_at')
            ->whereNull('reserved_at')
            ->count();
        
        return [
            'pending' => $pendingJobs,
            'failed' => $failedJobs,
            'available' => $availableJobs,
            'processing' => $processingJobs,
            'last_processed_at' => $lastProcessed ? date('Y-m-d H:i:s', $lastProcessed->reserved_at) : null,
            'is_worker_running' => $this->checkWorkerStatus(),
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
                return [
                    'id' => $job->id,
                    'queue' => $job->queue,
                    'payload' => $payload['displayName'] ?? 'Unknown',
                    'created_at' => date('Y-m-d H:i:s', $job->created_at),
                    'reserved_at' => $job->reserved_at ? date('Y-m-d H:i:s', $job->reserved_at) : null,
                ];
            });
        
        return response()->json([
            'success' => true,
            'jobs' => $pendingJobs
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

