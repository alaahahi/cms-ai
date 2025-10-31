<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد مباشر إلى data_cv</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0"><i class="fas fa-database"></i> استيراد مباشر إلى جدول data_cv</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> ملاحظات:</h6>
                    <ul class="mb-0">
                        <li>✅ الإدخال مباشر بدون فلترة</li>
                        <li>✅ يدعم CSV, TXT, Excel (XLSX, XLS)</li>
                        <li>✅ يكتشف الأعمدة تلقائياً (PHONE, NAME, LIVING/ADDRESS)</li>
                    </ul>
                </div>
                
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label"><strong>اختر الملف:</strong></label>
                        <input type="file" class="form-control" id="fileInput" accept=".csv,.txt,.xlsx,.xls" required>
                        <small class="text-muted">CSV, TXT, Excel (XLSX, XLS)</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-upload"></i> استيراد مباشر إلى data_cv
                    </button>
                </form>
                
                <div id="result" class="mt-4" style="display: none;"></div>
                <div id="progress" class="mt-3" style="display: none;">
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" style="width: 100%">
                            <span id="progressText">جاري الاستيراد...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        document.getElementById('importForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('fileInput');
            if (!fileInput.files.length) {
                alert('الرجاء اختيار ملف');
                return;
            }
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            
            document.getElementById('progress').style.display = 'block';
            document.getElementById('result').style.display = 'none';
            document.getElementById('progressText').textContent = 'جاري رفع الملف...';
            
            // إخفاء النموذج أثناء المعالجة
            document.getElementById('importForm').style.opacity = '0.5';
            document.getElementById('importForm').querySelector('button').disabled = true;
            
            try {
                const response = await fetch(`${API_BASE}/api/import-to-data-cv`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                document.getElementById('progressText').textContent = 'جاري معالجة البيانات...';
                
                if (!response.ok) {
                    throw new Error('خطأ من الخادم: ' + response.statusText);
                }
                
                const data = await response.json();
                
                document.getElementById('progress').style.display = 'none';
                document.getElementById('result').style.display = 'block';
                
                if (data.success) {
                    document.getElementById('result').innerHTML = `
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> تم الاستيراد بنجاح!</h5>
                            <hr>
                            <p><strong>تم الإدخال:</strong> ${data.imported} سجل</p>
                            <p><strong>الأخطاء:</strong> ${data.errors} سجل</p>
                            <p><strong>الإجمالي:</strong> ${data.total} سجل</p>
                            <hr>
                            <p class="mb-0"><i class="fas fa-database"></i> تم إدخال البيانات في جدول <strong>data_cv</strong></p>
                        </div>
                    `;
                } else {
                    document.getElementById('result').innerHTML = `
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> حدث خطأ</h5>
                            <p>${data.message || 'خطأ غير معروف'}</p>
                        </div>
                    `;
                }
            } catch (error) {
                document.getElementById('progress').style.display = 'none';
                document.getElementById('result').style.display = 'block';
                document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle"></i> حدث خطأ</h5>
                        <p>${error.message}</p>
                        <p class="mt-2"><small>تحقق من سجلات Laravel في: storage/logs/laravel.log</small></p>
                    </div>
                `;
            } finally {
                // إعادة تفعيل النموذج
                document.getElementById('importForm').style.opacity = '1';
                document.getElementById('importForm').querySelector('button').disabled = false;
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

