<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركة الهدف المباشر - بطاقة الشرق الأوسط</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for Animations and Style -->
    <style>
        body {
            background-color: #11162a; /* Blue background */
            color: white;
            overflow-x: hidden;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        .container {
            position: relative;
            z-index: 10;
        }

        .logo {
            max-width: 150px;
            margin: 20px auto;
        }

        .abstract-icon {
            position: absolute;
            font-size: 4rem;
            opacity: 0.1;
            animation: floatIcon 5s ease-in-out infinite;
        }

        .abstract-icon-1 {
            top: 10%;
            left: 20%;
            animation-duration: 6s;
        }

        .abstract-icon-2 {
            top: 50%;
            right: 15%;
            animation-duration: 7s;
        }

        .abstract-icon-3 {
            bottom: 20%;
            left: 10%;
            animation-duration: 8s;
        }

        @keyframes floatIcon {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .card-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            color: black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-pay {
            background-color: #11162a;
            border: none;
            color: white;
        }

        .btn-pay:hover {
            background-color: #11162a;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .abstract-icon {
                font-size: 2.5rem;
            }

            .logo {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>

<!-- Abstract Animating Icons -->
<i class="fas fa-circle abstract-icon abstract-icon-1"></i>
<i class="fas fa-square abstract-icon abstract-icon-2"></i>
<i class="fas fa-star abstract-icon abstract-icon-3"></i>

<div class="container text-center mt-5">
    <!-- Logo -->
    <img src="/asset/img/logo1.jpg" style="border-radius: 100px" alt="Logo" class="logo">
    
    <!-- Company and Card Names -->
    <h1 class="mt-3">شركة الهدف المباشر</h1>
    <h3>بطاقة الشرق الأوسط</h3>

    <div class="container text-center mt-5">
        <h1>عملية الدفع</h1>
        <h2>سعر البطاقة 85.000 دينار عراقي فقط لاغير</h2>

        <div class="card-form mt-4">
            <form action="{{ route('make-payment') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المشترك</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
    
                <div class="mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
    
                <div class="mb-3">
                    <label for="familyNames" class="form-label">اسماء افراد العائلة (اختياري)</label>
                    <textarea class="form-control" id="familyNames" name="familyNames"></textarea>
                </div>
                @if( request()->get('more') == 'true')               
                <div class="mb-3">
                    <label for="salse" class="form-label">اسم المندوب</label>
                    <input type="text" class="form-control" id="salse" name="salse"></input>
                </div>
                @endif
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">رقم البطاقة (اختياري)</label>
                    <input type="text" class="form-control" id="cardNumber" name="cardNumber">
                </div>
    
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان (اختياري)</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
    
                <button type="submit" class="btn btn-primary w-100">دفع</button>
            </form>
        </div>
    </div>

    <img src="/asset/img/card book2-images-15.jpg" style="width: 100%;border-radius: 20px" class="my-4" alt="Logo">
    <h1>تواصل معنا الان</h1>
    <a href="tel:'07715558558'"  tel="07715558558" style="font-size: 25px;color:#fff;text-decoration: none">07715558558</a>
    <br>
    <br>
    <div class="container">
        <footer class=" py-3 my-4 text-center  border-top">
            جميع الحقوق محفوظة شركة الهدف المباشر - العراق بغداد 2024-عملية الدفع بالتعاون مع كي كارد
          
         
        </footer>
      </div>
</div>

<!-- Bootstrap 5 JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
