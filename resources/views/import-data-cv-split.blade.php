<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد مقسم - data_cv</title>
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
        .part-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: white;
            transition: all 0.3s;
        }
        .part-card:hover {
            border-color: #667eea;
        }
        .part-card.imported {
            background: #d4edda;
            border-color: #28a745;
        }
        .part-card.importing {
            background: #fff3cd;
            border-color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-cut"></i> استيراد مقسم - تقسيم الملف ثم رفع الأجزاء</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> طريقة العمل:</h6>
                    <ol class="mb-0">
                        <li>ارفع الملف الكبير - سيتم تقسيمه تلقائياً</li>
                        <li>⚠️ قد يستغرق التقسيم عدة دقائق للملفات الكبيرة</li>
                        <li>سيتم تقسيم الملف إلى 10 أجزاء (أو العدد المحدد)</li>
                        <li>ارفع كل جزء على حدة - أسرع وأكثر استقراراً</li>
                    </ol>
                </div>
                
                <div id="splitProgress" style="display: none;" class="alert alert-warning">
                    <i class="fas fa-spinner fa-spin"></i> 
                    <strong>جاري التقسيم...</strong>
                    <p class="mb-0 mt-2">⏳ هذا قد يستغرق بضع دقائق للملفات الكبيرة. يرجى الانتظار...</p>
                </div>
                
                <!-- الخطوة 1: تقسيم الملف -->
                <div id="step1">
                    <h5 class="mb-3"><i class="fas fa-file-upload"></i> الخطوة 1: تقسيم الملف</h5>
                    <form id="splitForm">
                        <div class="mb-3">
                            <label class="form-label"><strong>اختر الملف:</strong></label>
                            <input type="file" class="form-control" id="fileInput" accept=".csv,.txt,.xlsx,.xls" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>عدد الأجزاء:</strong></label>
                            <input type="number" class="form-control" id="partsInput" value="10" min="2" max="20">
                            <small class="text-muted">سقسم الملف إلى هذا العدد من الأجزاء</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-cut"></i> تقسيم الملف
                        </button>
                    </form>
                </div>
                
                <!-- الخطوة 2: رفع الأجزاء -->
                <div id="step2" style="display: none;">
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-upload"></i> الخطوة 2: رفع الأجزاء</h5>
                    <div class="mb-3">
                        <button class="btn btn-success" onclick="importAllParts()">
                            <i class="fas fa-play"></i> رفع جميع الأجزاء تلقائياً
                        </button>
                        <button class="btn btn-secondary" onclick="resetProcess()">
                            <i class="fas fa-redo"></i> إعادة开始
                        </button>
                    </div>
                    <div id="partsList"></div>
                    <div id="summary" class="mt-4" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let splitFiles = [];
        let importedParts = 0;
        let totalImported = 0;
        let totalErrors = 0;
        
        document.getElementById('splitForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('fileInput');
            const parts = document.getElementById('partsInput').value;
            
            if (!fileInput.files.length) {
                alert('الرجاء اختيار ملف');
                return;
            }
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('parts', parts);
            
            const btn = e.target.querySelector('button[type="submit"]');
            const progressDiv = document.getElementById('splitProgress');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التقسيم...';
            progressDiv.style.display = 'block';
            
            try {
                const response = await fetch(`${API_BASE}/api/split-file`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // التحقق من الاستجابة أولاً
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error('خطأ من الخادم: ' + response.statusText + ' - ' + errorText);
                }
                
                // محاولة تحليل JSON
                let data;
                const responseText = await response.text();
                try {
                    data = JSON.parse(responseText);
                } catch (jsonError) {
                    console.error('JSON Parse Error:', jsonError);
                    console.error('Response Text:', responseText);
                    throw new Error('خطأ في تحليل الاستجابة من الخادم');
                }
                
                progressDiv.style.display = 'none';
                
                if (data.success) {
                    splitFiles = data.files;
                    document.getElementById('step1').style.opacity = '0.5';
                    document.getElementById('step2').style.display = 'block';
                    displayParts();
                    
                    // إظهار ملخص
                    const summaryDiv = document.createElement('div');
                    summaryDiv.className = 'alert alert-success mt-3';
                    summaryDiv.innerHTML = `
                        <h6><i class="fas fa-check-circle"></i> تم التقسيم بنجاح!</h6>
                        <p class="mb-0">تم تقسيم الملف إلى <strong>${data.parts}</strong> أجزاء بإجمالي <strong>${data.total_rows}</strong> سجل</p>
                    `;
                    document.getElementById('step1').appendChild(summaryDiv);
                } else {
                    const errorMsg = data.message || 'خطأ غير معروف';
                    alert('❌ خطأ في التقسيم:\n' + errorMsg);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-cut"></i> تقسيم الملف';
                }
            } catch (error) {
                progressDiv.style.display = 'none';
                console.error('Error:', error);
                const errorMsg = error.message || 'حدث خطأ غير معروف';
                alert('❌ خطأ في الاتصال:\n' + errorMsg + '\n\nتحقق من Console لمزيد من التفاصيل\nأو تحقق من storage/logs/laravel.log');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-cut"></i> تقسيم الملف';
                
                // إظهار رسالة تفصيلية في Console
                console.error('Full error:', error);
            }
        });
        
        function displayParts() {
            const container = document.getElementById('partsList');
            container.innerHTML = '';
            
            splitFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'part-card';
                card.id = 'part-' + file.part;
                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>الجزء ${file.part}</strong>
                            <span class="badge bg-info ms-2">${file.rows} سجل</span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-success" onclick="importPart(${index})" id="btn-${file.part}">
                                <i class="fas fa-upload"></i> رفع
                            </button>
                            <span id="status-${file.part}" class="ms-2"></span>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }
        
        async function importPart(index) {
            const file = splitFiles[index];
            const card = document.getElementById('part-' + file.part);
            const btn = document.getElementById('btn-' + file.part);
            const status = document.getElementById('status-' + file.part);
            
            card.classList.add('importing');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            status.innerHTML = '<span class="text-warning">جاري الرفع...</span>';
            
            try {
                const response = await fetch(`${API_BASE}/api/import-split-part`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        filename: file.filename
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    card.classList.remove('importing');
                    card.classList.add('imported');
                    btn.innerHTML = '<i class="fas fa-check"></i> تم';
                    status.innerHTML = `<span class="text-success">✅ ${data.imported} سجل</span>`;
                    importedParts++;
                    totalImported += data.imported;
                    totalErrors += data.errors;
                    updateSummary();
                } else {
                    card.classList.remove('importing');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-upload"></i> رفع';
                    status.innerHTML = `<span class="text-danger">❌ خطأ: ${data.message}</span>`;
                }
            } catch (error) {
                card.classList.remove('importing');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i> رفع';
                status.innerHTML = `<span class="text-danger">❌ ${error.message}</span>`;
            }
        }
        
        async function importAllParts() {
            if (!confirm(`هل تريد رفع جميع ${splitFiles.length} جزء؟`)) {
                return;
            }
            
            for (let i = 0; i < splitFiles.length; i++) {
                await importPart(i);
                // انتظار 500ms بين كل جزء
                await new Promise(resolve => setTimeout(resolve, 500));
            }
        }
        
        function updateSummary() {
            const summary = document.getElementById('summary');
            summary.style.display = 'block';
            summary.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fas fa-chart-bar"></i> ملخص الاستيراد</h6>
                    <p><strong>الأجزاء المرفوعة:</strong> ${importedParts} / ${splitFiles.length}</p>
                    <p><strong>إجمالي المستورد:</strong> ${totalImported} سجل</p>
                    <p><strong>الأخطاء:</strong> ${totalErrors} سجل</p>
                    ${importedParts === splitFiles.length ? '<p class="mb-0"><strong>✅ اكتمل الاستيراد بنجاح!</strong></p>' : ''}
                </div>
            `;
        }
        
        function resetProcess() {
            location.reload();
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

