<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة Queue Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            min-height: 100vh;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            color: white;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .page-header .nav-links {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .page-header .nav-links a {
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .page-header .nav-links a:hover,
        .page-header .nav-links a.active {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .main-container {
            padding: 20px;
        }
        
        .card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
            border: none;
            margin-bottom: 20px;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .stat-card.success { background: #d4edda; color: #155724; }
        .stat-card.warning { background: #fff3cd; color: #856404; }
        .stat-card.danger { background: #f8d7da; color: #721c24; }
        .stat-card.info { background: #cce5ff; color: #004085; }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 5px;
        }
        
        .status-indicator.active { background: #28a745; }
        .status-indicator.inactive { background: #dc3545; }
    </style>
</head>
<body>
    <!-- الهيدر -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="text-center">
                <h1><i class="fas fa-tasks"></i> إدارة Queue Worker</h1>
                <p class="text-white-50 mb-0">مراقبة وإدارة Jobs والتحقق من حالة Worker</p>
                <div class="nav-links">
                    <a href="{{ route('data-cv-manage') }}" class="{{ request()->routeIs('data-cv-manage') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> جميع البيانات
                    </a>
                    <a href="{{ route('data-cv-moved') }}" class="{{ request()->routeIs('data-cv-moved') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i> الأرقام المنقولة
                    </a>
                    <a href="{{ route('queue-manage') }}" class="{{ request()->routeIs('queue-manage') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i> إدارة Queue
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-container">
        <!-- إحصائيات -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-chart-bar"></i> الإحصائيات</h4>
            </div>
            <div class="card-body">
                <div class="row" id="statsContainer">
                    <div class="col-md-3">
                        <div class="stat-card info">
                            <h3 id="pendingJobs">0</h3>
                            <small>Jobs في الانتظار</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card success">
                            <h3 id="availableJobs">0</h3>
                            <small>Jobs جاهزة</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <h3 id="processingJobs">0</h3>
                            <small>Jobs قيد المعالجة</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card danger">
                            <h3 id="failedJobs">0</h3>
                            <small>Jobs فاشلة</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <strong>حالة Worker:</strong>
                    <span id="workerStatus" class="badge bg-secondary">
                        <span class="status-indicator inactive"></span>
                        غير معروف
                    </span>
                </div>
            </div>
        </div>

        <!-- Jobs في الانتظار -->
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-clock"></i> Jobs في الانتظار</h4>
                <div>
                    <button class="btn btn-light btn-sm me-2" onclick="clearAllPending()">
                        <i class="fas fa-trash"></i> حذف الكل
                    </button>
                    <button class="btn btn-light btn-sm" onclick="loadPendingJobs()">
                        <i class="fas fa-sync-alt"></i> تحديث
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="pendingJobsList" class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>النوع</th>
                                <th>Queue</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="pendingJobsTable">
                            <tr><td colspan="6" class="text-center">جاري التحميل...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jobs الفاشلة -->
        <div class="card">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Jobs الفاشلة</h4>
                <div>
                    <button class="btn btn-light btn-sm me-2" onclick="retryAllFailed()">
                        <i class="fas fa-redo"></i> إعادة المحاولة للكل
                    </button>
                    <button class="btn btn-light btn-sm" onclick="clearAllFailed()">
                        <i class="fas fa-trash"></i> حذف الكل
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="failedJobsList" class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>النوع</th>
                                <th>الخطأ</th>
                                <th>تاريخ الفشل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="failedJobsTable">
                            <tr><td colspan="5" class="text-center">جاري التحميل...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- إرشادات التشغيل الدائم (بدون sudo) -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-info-circle"></i> إرشادات التشغيل الدائم (سيرفر Shared - بدون sudo)</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>ملاحظة:</strong> لأنك على سيرفر Shared ولا تملك صلاحيات sudo، استخدم الطرق التالية:
                </div>

                <h5>طريقة 1: استخدام Cron Job (موصى به للسيرفر Shared) ⭐</h5>
                <div class="alert alert-success">
                    <strong>الأفضل للبيئات الإنتاجية!</strong>
                </div>
                <p><strong>الخطوات:</strong></p>
                <ol>
                    <li>اذهب إلى CPanel → <strong>Cron Jobs</strong></li>
                    <li>اختر <strong>Advanced (Cron Style)</strong></li>
                    <li>أضف الأوامر التالية:</li>
                </ol>
                <pre class="bg-dark text-light p-3 rounded"><code># Minute: * 
# Hour: * 
# Day: * 
# Month: * 
# Weekday: *

# Command (عدّل المسار):
cd /home/USERNAME/public_html/cms-ai && /usr/bin/php artisan queue:work database --sleep=3 --tries=3 --timeout=60 --stop-when-empty >> /home/USERNAME/public_html/cms-ai/storage/logs/queue-cron.log 2>&1</code></pre>
                
                <p><strong>ملاحظات:</strong></p>
                <ul>
                    <li>استبدل <code>USERNAME</code> باسم المستخدم الفعلي</li>
                    <li>استبدل <code>cms-ai</code> باسم المجلد الفعلي</li>
                    <li>هذا سيعمل Worker كل دقيقة ويقوم بمعالجة Jobs المتاحة</li>
                    <li><code>--stop-when-empty</code> يعني أنه سيتوقف بعد معالجة جميع Jobs</li>
                </ul>

                <h5 class="mt-4">طريقة 2: استخدام Screen (للاختبار والتشغيل المؤقت)</h5>
                <div class="alert alert-warning">
                    <strong>مؤقت:</strong> هذه الطريقة تحتاج SSH وتتوقف عند إغلاق SSH
                </div>
                <pre class="bg-dark text-light p-3 rounded"><code># 1. اتصل بـ SSH من CPanel → Terminal

# 2. شغّل Screen:
screen -S queue-worker

# 3. انتقل لمجلد المشروع:
cd /home/USERNAME/public_html/cms-ai

# 4. شغّل Worker:
php artisan queue:work database --sleep=3 --tries=3

# 5. احفظ الجلسة (اضغط Ctrl+A ثم D):
# للرجوع: screen -r queue-worker
# لعرض جميع الجلسات: screen -ls
# للخروج: exit</code></pre>

                <h5 class="mt-4">طريقة 3: استخدام nohup (بدون Screen)</h5>
                <pre class="bg-dark text-light p-3 rounded"><code># من SSH:
cd /home/USERNAME/public_html/cms-ai
nohup php artisan queue:work database --sleep=3 --tries=3 > storage/logs/queue-worker.log 2>&1 &

# للتحقق من العملية:
ps aux | grep queue:work

# لإيقاف العملية:
# ابحث عن PID أولاً
ps aux | grep queue:work
# ثم:
kill PID_NUMBER</code></pre>

                <h5 class="mt-4">طريقة 4: Laravel Task Scheduler (بديل متقدم)</h5>
                <p>استخدم Laravel Scheduler مع Cron Job واحد فقط:</p>
                <ol>
                    <li>في <code>app/Console/Kernel.php</code> أضف:</li>
                </ol>
                <pre class="bg-dark text-light p-3 rounded"><code>protected function schedule(Schedule $schedule)
{
    // تشغيل Queue Worker كل دقيقة
    $schedule->command('queue:work --stop-when-empty')
             ->everyMinute()
             ->withoutOverlapping()
             ->runInBackground();
}</code></pre>
                <ol start="2">
                    <li>في CPanel Cron Jobs أضف (مرة واحدة فقط):</li>
                </ol>
                <pre class="bg-dark text-light p-3 rounded"><code>* * * * * cd /home/USERNAME/public_html/cms-ai && php artisan schedule:run >> /dev/null 2>&1</code></pre>

                <div class="alert alert-danger mt-3">
                    <strong>⚠️ مهم:</strong>
                    <ul class="mb-0">
                        <li>تأكد من تحديث المسارات في جميع الأوامر</li>
                        <li>تأكد من وجود صلاحيات الكتابة على <code>storage/logs</code></li>
                        <li>راقب Logs بانتظام للتأكد من عمل Worker</li>
                        <li>للـ Shared Hosting، استخدم <strong>طريقة 1 (Cron Job)</strong> - الأسهل والأفضل</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const API_BASE = window.location.origin;

        // تحديث الإحصائيات
        function updateStats() {
            fetch(`${API_BASE}/api/queue/stats`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const stats = data.stats;
                    document.getElementById('pendingJobs').textContent = stats.pending || 0;
                    document.getElementById('availableJobs').textContent = stats.available || 0;
                    document.getElementById('processingJobs').textContent = stats.processing || 0;
                    document.getElementById('failedJobs').textContent = stats.failed || 0;
                    
                    // تحديث حالة Worker
                    const workerStatus = document.getElementById('workerStatus');
                    if (stats.is_worker_running) {
                        workerStatus.className = 'badge bg-success';
                        workerStatus.innerHTML = '<span class="status-indicator active"></span> يعمل';
                    } else {
                        workerStatus.className = 'badge bg-danger';
                        workerStatus.innerHTML = '<span class="status-indicator inactive"></span> متوقف';
                    }
                }
            })
            .catch(err => console.error('Error:', err));
        }

        // تحميل Jobs في الانتظار
        function loadPendingJobs() {
            fetch(`${API_BASE}/api/queue/pending`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('pendingJobsTable');
                        if (data.jobs.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center">لا توجد Jobs في الانتظار</td></tr>';
                        } else {
                            tbody.innerHTML = data.jobs.map(job => `
                                <tr>
                                    <td>${job.id}</td>
                                    <td>${job.payload}</td>
                                    <td>${job.queue || 'default'}</td>
                                    <td>${job.created_at}</td>
                                    <td>${job.reserved_at ? '<span class="badge bg-warning">قيد المعالجة</span>' : '<span class="badge bg-info">في الانتظار</span>'}</td>
                                    <td>
                                        ${!job.reserved_at ? `
                                            <button class="btn btn-sm btn-danger" onclick="deletePendingJob(${job.id})" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        ` : '<span class="text-muted">-</span>'}
                                    </td>
                                </tr>
                            `).join('');
                        }
                }
            })
            .catch(err => console.error('Error:', err));
        }

        // تحميل Jobs الفاشلة
        function loadFailedJobs() {
            fetch(`${API_BASE}/api/queue/failed`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('failedJobsTable');
                    if (data.jobs.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">لا توجد Jobs فاشلة</td></tr>';
                    } else {
                        tbody.innerHTML = data.jobs.map(job => {
                            let errorMessage = 'خطأ غير معروف';
                            try {
                                const error = JSON.parse(job.exception);
                                errorMessage = error.message || job.exception.substring(0, 100);
                            } catch (e) {
                                errorMessage = job.exception ? job.exception.substring(0, 100) : 'خطأ غير معروف';
                            }
                            
                            const jobId = job.uuid || job.id;
                            return `
                                <tr>
                                    <td>${jobId}</td>
                                    <td>${job.queue || 'default'}</td>
                                    <td><small title="${errorMessage}">${errorMessage.substring(0, 50)}...</small></td>
                                    <td>${new Date(job.failed_at).toLocaleString('ar')}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="retryJob('${jobId}')">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteJob('${jobId}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        }).join('');
                    }
                }
            })
            .catch(err => console.error('Error:', err));
        }

        // إعادة محاولة Job
        function retryJob(jobId) {
            if (!confirm('هل تريد إعادة محاولة هذا Job؟')) return;
            
            fetch(`${API_BASE}/api/queue/retry`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: jobId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadFailedJobs();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // إعادة محاولة الكل
        function retryAllFailed() {
            if (!confirm('هل تريد إعادة محاولة جميع Jobs الفاشلة؟')) return;
            
            fetch(`${API_BASE}/api/queue/retry`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: 'all' })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadFailedJobs();
                    updateStats();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // حذف Job
        function deleteJob(jobId) {
            if (!confirm('هل تريد حذف هذا Job نهائياً؟')) return;
            
            fetch(`${API_BASE}/api/queue/clear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: jobId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadFailedJobs();
                    updateStats();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // حذف الكل
        function clearAllFailed() {
            if (!confirm('هل تريد حذف جميع Jobs الفاشلة نهائياً؟')) return;
            
            fetch(`${API_BASE}/api/queue/clear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: 'all' })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadFailedJobs();
                    updateStats();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // حذف Job من الانتظار
        function deletePendingJob(jobId) {
            if (!confirm('هل تريد حذف هذا Job من الانتظار؟')) return;
            
            fetch(`${API_BASE}/api/queue/delete-pending`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: jobId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadPendingJobs();
                    updateStats();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // حذف كل Jobs في الانتظار
        function clearAllPending() {
            if (!confirm('هل تريد حذف جميع Jobs في الانتظار (غير المحجوزة)؟')) return;
            
            fetch(`${API_BASE}/api/queue/delete-pending`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ job_id: 'all' })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadPendingJobs();
                    updateStats();
                }
            })
            .catch(err => alert('خطأ: ' + err.message));
        }

        // تحديث تلقائي كل 5 ثواني
        setInterval(() => {
            updateStats();
        }, 5000);

        // تحديث تلقائي للـ Jobs كل 10 ثواني
        setInterval(() => {
            loadPendingJobs();
            loadFailedJobs();
        }, 10000);

        // تحميل أولي
        updateStats();
        loadPendingJobs();
        loadFailedJobs();
    </script>
</body>
</html>

