<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة بيانات data_cv</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            min-height: 100vh;
        }
        
        /* الهيدر */
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
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-checked-yes { background: #d4edda; color: #155724; }
        .status-checked-no { background: #f8d7da; color: #721c24; }
        .status-not-checked { background: #fff3cd; color: #856404; }
        .status-moved { background: #cce5ff; color: #004085; }
        
        /* تحسين الباجينيشن */
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }
        
        .pagination .page-link {
            color: #667eea;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .pagination .page-link:hover {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
            font-weight: bold;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        .pagination-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- الهيدر -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="text-center">
                <h1><i class="fas fa-database"></i> إدارة بيانات data_cv</h1>
                <p class="text-white-50 mb-0">استيراد وإدارة الأرقام والتحقق من الواتساب</p>
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
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-table"></i> 
                    @if(request()->routeIs('data-cv-moved'))
                        الأرقام المنقولة إلى extracted_phones
                    @else
                        جميع البيانات
                    @endif
                </h4>
            </div>
            <div class="card-body">
                @if(!isset($is_moved_page) || !$is_moved_page)
                <!-- قسم الاستيراد -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-upload"></i> استيراد بيانات جديدة</h5>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle"></i> تم الاستيراد بنجاح!</h6>
                            <p class="mb-1"><strong>تم الإدخال:</strong> {{ session('imported') }} سجل</p>
                            <p class="mb-1"><strong>الأخطاء:</strong> {{ session('errors') }} سجل</p>
                            <p class="mb-0"><strong>الإجمالي:</strong> {{ session('total') }} سجل</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> حدث خطأ</h6>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('data-cv-manage') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="file" accept=".csv,.txt,.xlsx,.xls" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-upload"></i> استيراد مباشر
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">يدعم CSV, TXT, Excel (XLSX, XLS)</small>
                    </form>
                </div>

                <hr>
                @endif

                <!-- قسم البحث والعرض -->
                <div class="mb-3">
                    <form method="GET" action="{{ request()->routeIs('data-cv-moved') ? route('data-cv-moved') : route('data-cv-manage') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="بحث في الرقم أو الاسم..." 
                                   value="{{ request('search') }}">
                        </div>
                        @if(!isset($is_moved_page) || !$is_moved_page)
                        <div class="col-md-3">
                            <select class="form-select" name="whatsapp_status">
                                <option value="">كل الحالات</option>
                                <option value="0" {{ request('whatsapp_status') == '0' ? 'selected' : '' }}>لم يتم التحقق</option>
                                <option value="1" {{ request('whatsapp_status') == '1' ? 'selected' : '' }}>موجود على واتساب</option>
                                <option value="2" {{ request('whatsapp_status') == '2' ? 'selected' : '' }}>غير موجود</option>
                                <option value="3" {{ request('whatsapp_status') == '3' ? 'selected' : '' }}>منقول</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning" onclick="checkSelected()">
                                    <i class="fab fa-whatsapp"></i> تحقق من المحدد
                                </button>
                                <button type="button" class="btn btn-info" onclick="moveSelectedToExtracted()">
                                    <i class="fas fa-exchange-alt"></i> نقل المحدد
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- الإحصائيات -->
                <div class="row mb-3">
                    @if(isset($is_moved_page) && $is_moved_page)
                    <div class="col-md-4">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5>{{ $stats['total_moved'] ?? 0 }}</h5>
                                <small>إجمالي المنقولة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5>{{ $stats['with_whatsapp'] ?? 0 }}</h5>
                                <small>موجود على واتساب</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h5>{{ $stats['without_whatsapp'] ?? 0 }}</h5>
                                <small>غير موجود</small>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5>{{ $stats['total'] ?? 0 }}</h5>
                                <small>إجمالي السجلات</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5>{{ $stats['on_whatsapp'] ?? 0 }}</h5>
                                <small>موجود على واتساب</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h5>{{ $stats['not_on_whatsapp'] ?? 0 }}</h5>
                                <small>غير موجود</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-info text-white">
                            <div class="card-body">
                                <h5>{{ $stats['moved'] ?? 0 }}</h5>
                                <small>منقول</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- جدول البيانات -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll" onchange="toggleAll()"></th>
                                <th>#</th>
                                <th>رقم الهاتف</th>
                                <th>الاسم</th>
                                <th>العنوان</th>
                                <th>حالة الواتساب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-checkbox" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td><strong>{{ isset($is_moved_page) && $is_moved_page ? $item->phone : $item->phone_number }}</strong></td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit((isset($is_moved_page) && $is_moved_page ? $item->note : $item->address) ?? '-', 50) }}</td>
                                    <td>
                                        @if($item->whatsapp_status === 1)
                                            <span class="status-badge status-checked-yes">✅ موجود</span>
                                        @elseif($item->whatsapp_status === 0)
                                            <span class="status-badge status-checked-no">❌ غير موجود</span>
                                        @elseif(isset($item->whatsapp_status) && $item->whatsapp_status === 3)
                                            <span class="status-badge status-moved">📤 منقول</span>
                                        @else
                                            <span class="status-badge status-not-checked">⏳ لم يتحقق</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($is_moved_page) || !$is_moved_page)
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-sm btn-primary" onclick="checkSingle({{ $item->id }})">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            @if($item->whatsapp_status === 1)
                                            <button class="btn btn-sm btn-success" onclick="moveToExtracted({{ $item->id }})">
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-muted"><i class="fas fa-check-circle text-success"></i> منقول</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-info">لا توجد بيانات</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($data) && method_exists($data, 'links'))
                <div class="pagination-info">
                    <strong>عرض {{ $data->firstItem() ?? 0 }} إلى {{ $data->lastItem() ?? 0 }} من {{ $data->total() }} سجل</strong>
                </div>
                <div class="d-flex justify-content-center">
                    {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const API_BASE = window.location.origin;

        function toggleAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        function getSelectedIds() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        async function checkSingle(id) {
            if (!confirm('هل تريد التحقق من هذا الرقم على واتساب؟')) return;
            
            try {
                const response = await fetch(`${API_BASE}/api/check-whatsapp-data-cv`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('✅ تم إرسال طلب التحقق');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('❌ خطأ: ' + data.message);
                }
            } catch (error) {
                alert('❌ خطأ: ' + error.message);
            }
        }

        async function checkSelected() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('الرجاء تحديد سجلات');
                return;
            }
            
            if (!confirm(`هل تريد التحقق من ${ids.length} رقم؟`)) return;
            
            try {
                const response = await fetch(`${API_BASE}/api/check-whatsapp-data-cv-batch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ ids: ids })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert(`✅ تم إرسال ${data.processed} رقم للتحقق`);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('❌ خطأ: ' + data.message);
                }
            } catch (error) {
                alert('❌ خطأ: ' + error.message);
            }
        }

        async function moveToExtracted(id) {
            if (!confirm('هل تريد نقل هذا السجل إلى جدول extracted_phones؟')) return;
            
            try {
                const response = await fetch(`${API_BASE}/api/move-data-cv-to-extracted`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('✅ تم النقل بنجاح');
                    location.reload();
                } else {
                    alert('❌ خطأ: ' + data.message);
                }
            } catch (error) {
                alert('❌ خطأ: ' + error.message);
            }
        }

        async function moveSelectedToExtracted() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('الرجاء تحديد سجلات');
                return;
            }
            
            if (!confirm(`هل تريد نقل ${ids.length} سجل إلى جدول extracted_phones؟\n(سيتم نقل الأرقام الموجودة على واتساب فقط)`)) return;
            
            try {
                const response = await fetch(`${API_BASE}/api/move-data-cv-to-extracted-batch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ ids: ids })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert(`✅ تم نقل ${data.moved} سجل بنجاح`);
                    location.reload();
                } else {
                    alert('❌ خطأ: ' + data.message);
                }
            } catch (error) {
                alert('❌ خطأ: ' + error.message);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

