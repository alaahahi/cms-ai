<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد مباشر - data_cv</title>
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
                @if(session('success'))
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle"></i> تم الاستيراد بنجاح!</h5>
                        <p class="mb-1"><strong>تم الإدخال:</strong> {{ session('imported') }} سجل</p>
                        <p class="mb-1"><strong>الأخطاء:</strong> {{ session('errors') }} سجل</p>
                        <p class="mb-0"><strong>الإجمالي:</strong> {{ session('total') }} سجل</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle"></i> حدث خطأ</h5>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> طريقة الاستخدام:</h6>
                    <ul class="mb-0">
                        <li>✅ ارفع ملف Excel (.xlsx, .xls) أو CSV (.csv, .txt)</li>
                        <li>✅ سيتم الإدخال مباشرة في جدول <strong>data_cv</strong></li>
                        <li>✅ بدون فلترة - كل البيانات تُدخل كما هي</li>
                        <li>✅ يكتشف الأعمدة تلقائياً: PHONE, NAME, LIVING/ADDRESS</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('import-data-cv-simple') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>اختر الملف:</strong></label>
                        <input type="file" class="form-control" name="file" accept=".csv,.txt,.xlsx,.xls" required>
                        <small class="text-muted">CSV, TXT, Excel (XLSX, XLS)</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-upload"></i> استيراد مباشر إلى data_cv
                    </button>
                </form>

                @if(session('processing'))
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-spinner fa-spin"></i> جاري المعالجة... قد يستغرق بضع دقائق
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

