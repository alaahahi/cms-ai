<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد وفلترة الأرقام - Zain</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .main-card {
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
        }
        .step-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        .step-card.active {
            border-left-color: #28a745;
            background: #f0fff4;
        }
        .phone-item {
            background: #f8f9fa;
            padding: 12px;
            margin: 5px 0;
            border-radius: 8px;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        .phone-item:hover {
            border-color: #667eea;
            background: #e7ecff;
        }
        .phone-item.selected {
            border-color: #28a745;
            background: #d4edda;
        }
        .stats-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 5px;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .city-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            display: inline-block;
            margin: 3px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-card card mt-3">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-filter"></i> استيراد وفلترة الأرقام (معالجة على السيرفر)</h3>
            </div>
            <div class="card-body">
                
                <!-- الخطوة 1: رفع الملف -->
                <div class="step-card" id="step1">
                        <h4><i class="fas fa-cloud-upload-alt"></i> الخطوة 1: رفع الملف</h4>
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="border border-2 border-dashed p-4 text-center rounded">
                            <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                            <i class="fas fa-file-csv fa-2x text-primary mb-3 ms-2"></i>
                            <h5>اسحب ملف CSV أو Excel (حتى 200MB)</h5>
                            <p class="text-muted">المعالجة تتم على السيرفر - آمن تماماً! ✅</p>
                            <p class="text-info"><i class="fas fa-info-circle"></i> يدعم CSV, TXT, Excel (XLSX, XLS) - حتى 200,000 رقم</p>
                            <input type="file" class="form-control mt-3" id="fileInput" accept=".csv,.txt,.xlsx,.xls" required>
                            <small class="text-muted d-block mt-2">✨ أصبح يدعم الملفات الكبيرة جداً</small>
                            <button type="submit" class="btn btn-primary btn-lg mt-3">
                                <i class="fas fa-upload"></i> رفع ومعالجة
                            </button>
                        </div>
                    </form>
                    <div id="uploadProgress" style="display: none;">
                        <div class="card mt-3 border-info">
                            <div class="card-body">
                                <h6 class="card-title text-info">
                                    <i class="fas fa-spinner fa-spin"></i> جاري معالجة الملف...
                                </h6>
                                <div class="progress mb-2" style="height: 30px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" 
                                         role="progressbar" 
                                         style="width: 100%"
                                         id="uploadProgressBar">
                                        <span id="uploadProgressText">جاري قراءة الملف...</span>
                                    </div>
                                </div>
                                <small class="text-muted" id="uploadStatus">
                                    <i class="fas fa-file-alt"></i> جاري تحليل الملف ومعالجة البيانات...
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الخطوة 2: فلترة البيانات -->
                <div class="step-card" id="step2" style="display: none;">
                    <h4><i class="fas fa-filter"></i> الخطوة 2: فلترة البيانات</h4>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <i class="fas fa-database fa-2x text-primary mb-2"></i>
                                    <h5 id="totalRecords">0</h5>
                                    <small class="text-muted">إجمالي السجلات</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                    <h5 id="displayedRecords">0</h5>
                                    <small class="text-muted">معروض</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h5 id="filteredRecords">0</h5>
                                    <small class="text-muted">مفلتر</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>فلترة حسب العنوان:</strong></label>
                            <input type="text" class="form-control" id="filterAddress" 
                                   placeholder="مثال: بغداد، بابل، الموصل"
                                   onkeyup="debounceFilter()">
                            <small class="text-muted">اتركه فارغاً للكل</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>طريقة الفلترة:</strong></label>
                            <select class="form-select" id="filterMethod" onchange="applyFilter()">
                                <option value="contains">يحتوي على</option>
                                <option value="start">يبدأ بـ</option>
                                <option value="end">ينتهي بـ</option>
                            </select>
                        </div>
                    </div>
                        <button class="btn btn-success mt-3" id="applyFilterBtn" onclick="applyFilter()">
                            <i class="fas fa-search"></i> تطبيق الفلتر
                        </button>
                        <button class="btn btn-secondary mt-3 ms-2" onclick="resetFilter()">
                            <i class="fas fa-redo"></i> إعادة تعيين
                        </button>
                    <div id="filterProgress" style="display: none;" class="mt-3">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-spinner fa-spin"></i> 
                            <span id="filterProgressText">جاري تطبيق الفلتر...</span>
                        </div>
                    </div>
                </div>

                <!-- الخطوة 3: عرض البيانات -->
                <div class="step-card" id="step3" style="display: none;">
                    <h4><i class="fas fa-list"></i> الخطوة 3: اختيار الأرقام</h4>
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle"></i> عدد الأرقام المفلترة: <span id="filteredCount">0</span>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-sm btn-success" onclick="selectAll()">
                            <i class="fas fa-check-double"></i> تحديد الكل
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deselectAll()">
                            <i class="fas fa-times"></i> إلغاء التحديد
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="showSelected()">
                            <i class="fas fa-eye"></i> عرض المحدد (<span id="selectedCount">0</span>)
                        </button>
                        <button class="btn btn-sm btn-success" onclick="checkSelected()">
                            <i class="fas fa-check-circle"></i> التحقق من المحدد
                        </button>
                    </div>
                    <div id="phoneList" style="max-height: 500px; overflow-y: auto;"></div>
                </div>

                <!-- الخطوة 4: التحقق من الواتساب -->
                <div class="step-card" id="step4" style="display: none;">
                    <h4><i class="fab fa-whatsapp"></i> الخطوة 4: التحقق من الواتساب</h4>
                    
                    <!-- حالة العملية -->
                    <div class="alert alert-primary mb-3" id="statusAlert">
                        <h5 class="mb-2">
                            <i class="fas fa-info-circle"></i> <span id="statusTitle">معلومات العملية</span>
                        </h5>
                        <p id="progressDetails" class="mb-0">جاري بدء عملية الاستيراد والتحقق...</p>
                    </div>
                    
                    <!-- شريط التقدم الرئيسي -->
                    <div class="card mb-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong><i class="fas fa-tasks"></i> التقدم الإجمالي</strong>
                                <span id="progressPercentage" class="badge bg-primary" style="font-size: 16px;">0%</span>
                            </div>
                            <div class="progress" style="height: 40px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             id="progressBar"
                             style="width: 0%">
                                    <strong id="progressText" style="line-height: 40px;">0%</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- الإحصائيات -->
                    <div class="row mb-3">
                        <div class="col-md-3 text-center mb-2">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="fas fa-download fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0" id="importedCount">0</h4>
                                    <small class="text-muted">تم الاستيراد</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-2">
                            <div class="card border-info">
                                <div class="card-body">
                                    <i class="fas fa-sync fa-2x text-info mb-2"></i>
                                    <h4 class="mb-0" id="checkedCount">0</h4>
                                    <small class="text-muted">تم التحقق</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-2">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0" id="onWhatsAppCount">0</h4>
                                    <small class="text-muted">على واتساب</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-2">
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <i class="fas fa-times-circle fa-2x text-secondary mb-2"></i>
                                    <h4 class="mb-0" id="notOnWhatsAppCount">0</h4>
                                    <small class="text-muted">غير موجود</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- تفاصيل العملية -->
                    <div class="card mb-3" id="processDetailsCard">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-list"></i> تفاصيل العملية</h6>
                        </div>
                        <div class="card-body">
                            <div id="processLog" style="max-height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                                <div class="text-muted">في انتظار بدء العملية...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- رسالة الحالة -->
                    <div id="statusMessage" class="alert alert-warning mb-3">
                        <i class="fas fa-clock"></i> <span id="statusMessageText">جاري المعالجة...</span>
                    </div>
                    
                    <button class="btn btn-primary btn-lg w-100 mb-2" onclick="window.location.href='/whatsapp-checker'" id="viewResultsBtn" style="display: none;">
                        <i class="fas fa-chart-bar"></i> متابعة النتائج والإحصائيات
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin;
        let serverData = [];
        let filteredData = [];
        let selectedIndexes = [];
        let filterTimeout = null;
        
        // التأكد من وجود جميع العناصر المطلوبة
        function checkElements() {
            const requiredElements = [
                'uploadProgress', 'uploadProgressText', 'uploadProgressBar', 'uploadStatus',
                'totalRecords', 'displayedRecords', 'filteredRecords',
                'step1', 'step2', 'step3', 'step4'
            ];
            
            const missing = requiredElements.filter(id => !document.getElementById(id));
            if (missing.length > 0) {
                console.error('Missing elements:', missing);
                return false;
            }
            return true;
        }
        
        // تشغيل الفحص عند تحميل الصفحة
        window.addEventListener('DOMContentLoaded', function() {
            if (!checkElements()) {
                console.warn('Some required elements are missing. The page may not work correctly.');
            }
        });

        // رفع الملف
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('file', document.getElementById('fileInput').files[0]);
            
            document.getElementById('uploadProgress').style.display = 'block';
            document.getElementById('step1').querySelector('h4').innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري معالجة الملف...';
            
            try {
                // تحديث حالة التقدم
                document.getElementById('uploadProgressText').textContent = 'جاري رفع الملف...';
                document.getElementById('uploadStatus').innerHTML = '<i class="fas fa-upload"></i> جاري رفع الملف إلى السيرفر...';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const response = await fetch(`${API_BASE}/api/upload-process-csv`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // تحديث حالة التقدم
                if (!response.ok) {
                    throw new Error('فشل رفع الملف: ' + response.statusText);
                }
                
                document.getElementById('uploadProgressText').textContent = 'جاري معالجة البيانات...';
                document.getElementById('uploadStatus').innerHTML = '<i class="fas fa-cog fa-spin"></i> جاري تحليل الملف ومعالجة البيانات...';
                
                const data = await response.json();
                console.log('Received data:', data);
                
                if (data.success) {
                    serverData = data.data || [];
                    filteredData = serverData;
                    
                    console.log('Total records:', data.total_records);
                    console.log('Displayed records:', data.displayed_records);
                    console.log('Sample data:', data.sample);
                    
                    // تحديث الإحصائيات
                    document.getElementById('totalRecords').textContent = data.total_records || 0;
                    document.getElementById('displayedRecords').textContent = data.displayed_records || 0;
                    document.getElementById('filteredRecords').textContent = filteredData.length || 0;
                    
                    // تحديث حالة التقدم
                    document.getElementById('uploadProgressText').textContent = 'تم المعالجة بنجاح!';
                    document.getElementById('uploadProgressBar').classList.remove('bg-info');
                    document.getElementById('uploadProgressBar').classList.add('bg-success');
                    document.getElementById('uploadStatus').innerHTML = `
                        <i class="fas fa-check-circle text-success"></i> 
                        تم معالجة ${data.total_records} سجل بنجاح
                        ${data.has_more ? ' <span class="text-warning">⚠️ تم تقليل العرض للأداء</span>' : ''}
                    `;
                    
                    document.getElementById('step2').style.display = 'block';
                    document.getElementById('step1').classList.add('active');
                    
                    setTimeout(() => {
                    document.getElementById('uploadProgress').style.display = 'none';
                    document.getElementById('step1').querySelector('h4').innerHTML = '<i class="fas fa-check-circle text-success"></i> الخطوة 1: تم رفع الملف بنجاح!';
                    }, 2000);
                    
                    // عرض عينة من البيانات
                    if (data.sample && data.sample.length > 0) {
                        console.log('Sample:', data.sample[0]);
                    }
                }
                } catch (error) {
                console.error('Error:', error);
                document.getElementById('uploadProgressText').textContent = 'حدث خطأ!';
                document.getElementById('uploadProgressBar').classList.remove('bg-info', 'bg-success');
                document.getElementById('uploadProgressBar').classList.add('bg-danger');
                document.getElementById('uploadStatus').innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> خطأ: ${error.message}`;
                alert('حدث خطأ: ' + error.message);
                setTimeout(() => {
                    document.getElementById('uploadProgress').style.display = 'none';
                }, 3000);
            }
        });

        function debounceFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilter, 500);
        }

        async function applyFilter() {
            const filter = document.getElementById('filterAddress').value;
            const method = document.getElementById('filterMethod').value;
            
            console.log('Applying filter:', filter, 'method:', method);
            
            // إظهار loading
            const btn = document.getElementById('applyFilterBtn');
            const filterProgressDiv = document.getElementById('filterProgress');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الفلترة...';
            filterProgressDiv.style.display = 'block';
            document.getElementById('filterProgressText').textContent = 'جاري تطبيق الفلتر...';
            
            // فلترة محلية أولاً لسرعة الاستجابة
            if (!filter || filter.trim() === '') {
                filteredData = serverData;
                document.getElementById('filteredRecords').textContent = filteredData.length;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-search"></i> تطبيق الفلتر';
                filterProgressDiv.style.display = 'none';
            } else {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    const response = await fetch(`${API_BASE}/api/filter-import-data`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            data: serverData,
                            filter: filter.toLowerCase().trim(),
                            filter_method: method
                        })
                    });
                    
                    const result = await response.json();
                    console.log('Filter result:', result);
                    
                    if (result.success) {
                        filteredData = result.filtered_data || [];
                        const count = result.count || 0;
                        document.getElementById('filteredCount').textContent = count;
                        document.getElementById('filteredRecords').textContent = count;
                        document.getElementById('filterProgressText').textContent = `تم العثور على ${count} نتيجة`;
                        document.getElementById('filterProgressText').classList.remove('text-info');
                        document.getElementById('filterProgressText').classList.add('text-success');
                    } else {
                        alert('حدث خطأ: ' + (result.message || 'غير معروف'));
                        document.getElementById('filterProgressText').textContent = 'حدث خطأ في الفلترة';
                        document.getElementById('filterProgressText').classList.add('text-danger');
                    }
                } catch (error) {
                    console.error('Filter error:', error);
                    alert('حدث خطأ في الفلترة: ' + error.message);
                    document.getElementById('filterProgressText').textContent = 'حدث خطأ: ' + error.message;
                    document.getElementById('filterProgressText').classList.add('text-danger');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-search"></i> تطبيق الفلتر';
                    setTimeout(() => {
                        document.getElementById('filterProgress').style.display = 'none';
                    }, 2000);
                }
            }
            
            document.getElementById('filteredCount').textContent = filteredData.length;
            selectedIndexes = []; // إعادة تعيين التحديد
            displayData();
        }

        function resetFilter() {
            document.getElementById('filterAddress').value = '';
            document.getElementById('filterMethod').value = 'contains';
            filteredData = serverData;
            document.getElementById('filteredCount').textContent = filteredData.length;
            document.getElementById('filteredRecords').textContent = filteredData.length;
            selectedIndexes = [];
            document.getElementById('filterProgress').style.display = 'none';
            displayData();
        }

        function displayData() {
            const container = document.getElementById('phoneList');
            container.innerHTML = '';
            
            if (!filteredData || filteredData.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">لا توجد بيانات للعرض</div>';
                return;
            }
            
            // عرض أول 500 رقم فقط للأداء
            const displayData = filteredData.slice(0, 500);
            
            console.log('Displaying data:', displayData.length, 'items');
            
            displayData.forEach((row, index) => {
                const item = document.createElement('div');
                item.className = 'phone-item';
                item.id = 'item-' + index;
                
                if (selectedIndexes.includes(index)) {
                    item.classList.add('selected');
                }
                
                let cleanPhone = (row.phone || '').replace(/[^0-9]/g, '');
                if (cleanPhone.startsWith('964')) {
                    cleanPhone = '0' + cleanPhone.substring(3);
                }
                
                const name = row.name || 'غير محدد';
                const address = row.address || 'غير محدد';
                
                item.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <input type="checkbox" id="cb-${index}" 
                                   ${selectedIndexes.includes(index) ? 'checked' : ''}
                                   onchange="toggleSelect(${index})">
                        </div>
                        <div class="col-md-2"><strong><i class="fas fa-phone"></i> ${cleanPhone || 'غير محدد'}</strong></div>
                        <div class="col-md-3"><i class="fas fa-user"></i> ${name}</div>
                        <div class="col-md-6"><i class="fas fa-map-marker-alt"></i> ${address}</div>
                    </div>
                `;
                
                item.onclick = () => toggleSelect(index);
                container.appendChild(item);
            });
            
            if (filteredData.length > 500) {
                container.innerHTML += `<div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle"></i> عرض أول 500 رقم من ${filteredData.length}. 
                    استخدم الفلتر لتضييق النتائج.
                </div>`;
            }
            
            updateSelectedCount();
        }

        function toggleSelect(index) {
            const checkbox = document.getElementById('cb-' + index);
            const item = document.getElementById('item-' + index);
            
            if (selectedIndexes.includes(index)) {
                selectedIndexes = selectedIndexes.filter(i => i !== index);
                checkbox.checked = false;
                item.classList.remove('selected');
            } else {
                selectedIndexes.push(index);
                checkbox.checked = true;
                item.classList.add('selected');
            }
            updateSelectedCount();
        }

        function selectAll() {
            selectedIndexes = filteredData.slice(0, 200).map((_, i) => i);
            filteredData.slice(0, 200).forEach((_, index) => {
                document.getElementById('cb-' + index).checked = true;
                document.getElementById('item-' + index).classList.add('selected');
            });
            updateSelectedCount();
        }

        function deselectAll() {
            selectedIndexes.forEach(index => {
                const checkbox = document.getElementById('cb-' + index);
                const item = document.getElementById('item-' + index);
                if (checkbox) checkbox.checked = false;
                if (item) item.classList.remove('selected');
            });
            selectedIndexes = [];
            updateSelectedCount();
        }

        function updateSelectedCount() {
            document.getElementById('selectedCount').textContent = selectedIndexes.length;
        }

        function showSelected() {
            alert(`تم تحديد ${selectedIndexes.length} رقم`);
        }

        async function checkSelected() {
            if (selectedIndexes.length === 0) {
                alert('الرجاء تحديد أرقام');
                return;
            }
            
            if (!confirm(`هل تريد التحقق من ${selectedIndexes.length} رقم؟`)) {
                return;
            }
            
            const selectedPhones = selectedIndexes.map(i => filteredData[i]);
            
            // معالجة الأرقام
            for (let i = 0; i < selectedPhones.length; i++) {
                let cleanPhone = selectedPhones[i].phone.replace(/[^0-9]/g, '');
                if (cleanPhone.startsWith('964')) {
                    cleanPhone = '0' + cleanPhone.substring(3);
                }
                selectedPhones[i].phone = cleanPhone;
            }
            
            document.getElementById('step4').style.display = 'block';
            document.getElementById('step3').classList.add('active');
            
            // تحديث الواجهة
            addProcessLog('بدء عملية الاستيراد والتحقق...');
            updateProgress(0, `جاري إرسال ${selectedPhones.length} رقم...`);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const response = await fetch(`${API_BASE}/api/import-check-phones`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        phones: selectedPhones,
                        delay_seconds: 3
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    addProcessLog(`✅ تم إرسال ${data.to_check || 0} رقم للتحقق من الواتساب`);
                    addProcessLog(`➕ تم إضافة ${data.added || 0} رقم جديد`);
                    addProcessLog(`⏭️ تم تخطي ${data.existing || 0} رقم (موجود مسبقاً)`);
                    
                    document.getElementById('importedCount').textContent = data.added || 0;
                    document.getElementById('progressDetails').innerHTML = `
                        <i class="fas fa-check-circle text-success"></i> 
                        تم إرسال ${data.to_check || 0} رقم للتحقق من الواتساب
                    `;
                    
                    updateProgress(50, `تم إرسال ${data.to_check || 0} رقم للتحقق...`);
                    document.getElementById('statusMessageText').textContent = 
                        `تم إرسال ${data.to_check || 0} رقم للتحقق. جاري المعالجة في الخلفية...`;
                    
                    // بدء متابعة التقدم
                    startProgressTracking(selectedPhones.length);
                } else {
                    addProcessLog(`❌ حدث خطأ: ${data.message || 'غير معروف'}`);
                    document.getElementById('statusAlert').classList.remove('alert-primary');
                    document.getElementById('statusAlert').classList.add('alert-danger');
                }
            } catch (error) {
                console.error(error);
                addProcessLog(`❌ خطأ: ${error.message}`);
                document.getElementById('statusAlert').classList.remove('alert-primary');
                document.getElementById('statusAlert').classList.add('alert-danger');
                alert('حدث خطأ: ' + error.message);
            }
        }
        
        function updateProgress(percentage, text) {
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = text || percentage + '%';
            document.getElementById('progressPercentage').textContent = percentage + '%';
        }
        
        function addProcessLog(message) {
            const logDiv = document.getElementById('processLog');
            const time = new Date().toLocaleTimeString('ar-IQ');
            const logEntry = document.createElement('div');
            logEntry.className = 'mb-1';
            logEntry.textContent = `[${time}] ${message}`;
            logDiv.appendChild(logEntry);
            logDiv.scrollTop = logDiv.scrollHeight;
        }
        
        function startProgressTracking(total) {
            // متابعة التقدم كل 5 ثوان
            const interval = setInterval(async () => {
                try {
                    // يمكنك إضافة API endpoint لمتابعة التقدم الحقيقي
                    // حالياً سنعرض رسالة عامة
                    document.getElementById('statusMessageText').textContent = 
                        'جاري التحقق من الأرقام في الخلفية. يرجى الانتظار...';
                } catch (error) {
                    console.error('Progress tracking error:', error);
                }
            }, 5000);
            
            // إيقاف المتابعة بعد 5 دقائق
            setTimeout(() => {
                clearInterval(interval);
                document.getElementById('viewResultsBtn').style.display = 'block';
                document.getElementById('statusMessage').classList.remove('alert-warning');
                document.getElementById('statusMessage').classList.add('alert-success');
                document.getElementById('statusMessageText').textContent = 
                    '✅ تم إكمال العملية! يمكنك متابعة النتائج من صفحة الإحصائيات.';
                updateProgress(100, 'اكتمل');
            }, 300000); // 5 دقائق
        }

        // تصدير الدوال للـ buttons
        window.selectAll = selectAll;
        window.deselectAll = deselectAll;
        window.showSelected = showSelected;
        window.toggleSelect = toggleSelect;
        window.applyFilter = applyFilter;
        window.debounceFilter = debounceFilter;
        window.checkSelected = checkSelected;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // إعداد CSRF token لجميع fetch requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // دالة مساعدة لإضافة CSRF token للـ fetch requests
        function fetchWithCSRF(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            
            if (options.headers) {
                defaultOptions.headers = {...defaultOptions.headers, ...options.headers};
            }
            
            return fetch(url, {...options, ...defaultOptions});
        }
    </script>
</body>
</html>




