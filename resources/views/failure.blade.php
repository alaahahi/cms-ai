<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .error-icon {
            font-size: 5rem;
            color: red;
        }
    </style>
</head>
<body>

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Red Cross Icon -->
            <i class="fas fa-times-circle error-icon"></i>
            <!-- Payment Failure Message -->
            <h1 class="mt-4">فشلت عملية الدفع</h1>
            <p class="lead">حدث خطأ أثناء معالجة الدفع. يرجى المحاولة مرة أخرى.</p>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
