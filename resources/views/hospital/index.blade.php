<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="ar" dir="rtl">
<head>

  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <title>{{$hospital['name'] ?? ''}}</title>

  <!-- Mobile Specific Metas
  ================================================== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="{{$hospital['name'] ?? ''}}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="author" content="Themefisher">
  <meta name="generator" content="{{$hospital['name'] ?? ''}}">
  <meta name="googlebot" content="index">
  <meta name="robots" content="index, follow">
  <meta name="robots" content="max-image-preview:large">
  <meta name="msapplication-TileColor" content="#d10404">
  <link rel="shortcut icon" href="./web-asset/img/favicon.ico">
  <link rel="apple-touch-icon" href="./web-asset/img/favicon.ico">
  <!-- theme meta -->
  <meta name="theme-name" content="{{$hospital['name'] ?? ''}}" />

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="par/images/favicon.png" />

  <!-- 
  Essential stylesheets
  =====================================-->
  <link rel="stylesheet" href="par/plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="par/plugins/icofont/icofont.min.css">
  <link rel="stylesheet" href="par/plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="par/plugins/slick-carousel/slick/slick-theme.css">

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="par/css/style.css">

</head>

<body id="top">

<header>
	<div class="header-top-bar">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="text-center text-lg-right top-right-bar mt-2 mt-lg-0">
						<a href="tel:{{$hospital['phone'] ?? ''}}">
							<span>اتصل الان : </span>
							<span class="h4" dir='ltr'>{{$hospital['phone'] ?? ''}}</span>
						</a>
					</div>
				</div>
				<div class="col-lg-6">
					<ul class=" text-center top-bar-info list-inline-item pl-0 mb-0">
						<li class="list-inline-item"><a href="mailto:{{$hospital['email'] ?? ''}}"><i class="icofont-support-faq mr-2"></i>{{$hospital['email'] ?? ''}}</a></li>
						<li class="list-inline-item" dir='ltr'><i class="icofont-location-pin mr-2"></i>{{$hospital['address'] ?? ''}} </li>
					</ul>
				</div>
		
			</div>
		</div>
	</div>
	<nav class="navbar navbar-expand-lg navigation" id="navbar">
		<div class="container">
			<a class="navbar-brand" href="/">
				<img src="images/logo.png" alt="" class="img-fluid">
			</a>

			<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain"
				aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
				<span class="icofont-navigation-menu"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarmain">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active"><a class="nav-link" href="/">الرئيسية</a></li>
					<li class="nav-item"><a class="nav-link" href="#about">حول {{$hospital['name'] ?? ''}}</a></li>
					<li class="nav-item"><a class="nav-link" href="#service">عيادات البطاقة المجانية</a></li>
					<li class="nav-item"><a class="nav-link" href="#service">أقسام المستشفى</a></li>
					<li class="nav-item"><a class="nav-link" href="#service">شروط الاستخدام  </a></li>
		
					{{-- <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="doctor.html" id="dropdown03" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">Doctors <i class="icofont-thin-down"></i></a>
						<ul class="dropdown-menu" aria-labelledby="dropdown03">
							<li><a class="dropdown-item" href="doctor.html">Doctors</a></li>
							<li><a class="dropdown-item" href="doctor-single.html">Doctor Single</a></li>
							<li><a class="dropdown-item" href="appoinment.html">Appoinment</a></li>

							<li class="dropdown dropdown-submenu dropleft">
								<a class="dropdown-item dropdown-toggle" href="#!" id="dropdown0501" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sub Menu</a>
			
								<ul class="dropdown-menu" aria-labelledby="dropdown0501">
									<li><a class="dropdown-item" href="index.html">Submenu 01</a></li>
									<li><a class="dropdown-item" href="index.html">Submenu 02</a></li>
								</ul>
							</li>
						</ul>
					</li> --}}


					<li class="nav-item"><a class="nav-link" href="#contact">معلومات التواصل</a></li>
				</ul>
			</div>
		</div>
	</nav>
</header>


<!-- Slider Start -->
<section class="banner">
	<div class="container">
		<div class="row justify-content-end">
			<div class="col-lg-6 col-md-12 col-xl-7 text-center">
				<div class="block">
					<div class="divider mb-3" style="width: 100%"></div>
					<span class="text-uppercase text-sm  ">حلول الرعاية الصحية الشاملة</span>
					<h1 class="mb-3 mt-3">{{$hospital['name'] ?? ''}}</h1>
					
					<p class="mb-4 pr-5">{{$hospital['title2'] ?? ''}}</p>
					<div class="btn-container ">
						<a href="tel:{{$hospital['phone'] ?? ''}}" class="btn btn-main-2 btn-icon btn-round-full">
							<i class="icofont-simple-right ml-2  "></i>
							حجز موعد </a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="features">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="feature-block d-lg-flex">
					<div class="feature-item mb-5 mb-lg-0 text-center">
						<div class="feature-icon mb-4">
							<i class="icofont-surgeon-alt"></i>
						</div>
						<span>خدمة 24 ساعة</span>
						<h4 class="mb-3">حجز موعد</h4>
						<p class="mb-4">احصل على الدعم طوال الوقت لحالات الطوارئ. خدمة مميزة لكل الأسرة.</p>
						<a href="appoinment" class="btn btn-main btn-round-full">حجز موعد</a>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0 text-center">
						<div class="feature-icon mb-4">
							<i class="icofont-ui-clock"></i>
						</div>
						<span>جدوال الدوام</span>
						<h4 class="mb-3">ساعات العمل</h4>
						<ul class="w-hours list-unstyled">
							<li class="d-flex justify-content-between">Sun - Wed : <span>8:00 - 11:00</span></li>
							<li class="d-flex justify-content-between">Thu - Fri : <span>8:00 - 11:00</span></li>
							<li class="d-flex justify-content-between">Sat - sun : <span>8:00 - 11:00</span></li>
						</ul>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0 text-center">
						<div class="feature-icon mb-4">
							<i class="icofont-support"></i>
						</div>
						<span>حالات الطوارئ</span>
						<h4 class="mb-3" dir='ltr'> </h4>
						<p>{{$hospital['title2'] ?? ''}}</p>
						<p> كشفية الطوارئ مجانية</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="section about">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-4 col-sm-6">
				<div class="about-img">
					<img src="images/about/img-1.jpg" alt="" class="img-fluid">
					<img src="images/about/img-2.jpg" alt="" class="img-fluid mt-4">
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="about-img mt-4 mt-lg-0">
					<img src="images/about/img-3.jpg" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-12 text-center">
				<div class="about-content pl-4 mt-4 mt-lg-0">
					<h2 class="title-color">{{$hospital['title1'] ?? ''}}</h2>
					<p class="mt-4 mb-5">{{$hospital['title3'] ?? ''}}</p>

					<a href="service" class="btn btn-main-2 btn-round-full btn-icon">خصوماتنا </a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="cta-section ">
	<div class="container">
		<div class="cta position-relative">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-doctor"></i>
						<span class="h3 counter" data-count="100">0</span>%
						<p>خصم حتى </p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-flag"></i>
						<span class="h3 counter" data-count="700">0</span>+
						<p>مريض مستفيدة</p>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-badge"></i>
						<span class="h3 counter" data-count="20">0</span>+
						<p>طبيب خبير</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-globe"></i>
						<span class="h3 counter" data-count="6">0</span>
						<p>عيادات البطاقة المجانية</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section service gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>عيادات البطاقة المجانية</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{$hospital['title1'] ?? ''}}</p>
					<p>{{$hospital['title2'] ?? ''}}</p>
					<p>خصم بنسبة 100%</p>

				</div>
			</div>
		</div>
		{!! $hospital['doctor'] ?? '' !!}		
	</div>
</section>
{{-- <section class="section appoinment">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 ">
				<div class="appoinment-content">
					<img src="images/about/img-3.jpg" alt="" class="img-fluid">
					<div class="emergency">
						<h2 class="text-lg"><i class="icofont-phone-circle text-lg"></i>+23 345 67980</h2>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-10 ">
				<div class="appoinment-wrap mt-5 mt-lg-0">
					<h2 class="mb-2 title-color">Book appoinment</h2>
					<p class="mb-4">Mollitia dicta commodi est recusandae iste, natus eum asperiores corrupti qui velit . Iste dolorum atque similique praesentium soluta.</p>
					     <form id="#" class="appoinment-form" method="post" action="#">
                    <div class="row">
                         <div class="col-lg-6">
                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect1">
                                  <option>Choose Department</option>
                                  <option>Software Design</option>
                                  <option>Development cycle</option>
                                  <option>Software Development</option>
                                  <option>Maintenance</option>
                                  <option>Process Query</option>
                                  <option>Cost and Duration</option>
                                  <option>Modal Delivery</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect2">
                                  <option>Select Doctors</option>
                                  <option>Software Design</option>
                                  <option>Development cycle</option>
                                  <option>Software Development</option>
                                  <option>Maintenance</option>
                                  <option>Process Query</option>
                                  <option>Cost and Duration</option>
                                  <option>Modal Delivery</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="date" id="date" type="text" class="form-control" placeholder="dd/mm/yyyy">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <input name="time" id="time" type="text" class="form-control" placeholder="Time">
                            </div>
                        </div>
                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="name" id="name" type="text" class="form-control" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <input name="phone" id="phone" type="Number" class="form-control" placeholder="Phone Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group-2 mb-4">
                        <textarea name="message" id="message" class="form-control" rows="6" placeholder="Your Message"></textarea>
                    </div>

                    <a class="btn btn-main btn-round-full" href="appoinment.html" >Make Appoinment <i class="icofont-simple-right ml-2  "></i></a>
                </form>
            </div>
			</div>
		</div>
	</div>
</section> --}}

<section class=" mt-5">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>{{$hospital['name'] ?? ''}}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{!! $hospital['title4'] ?? '' !!}</p>
					<p>{!! $hospital['title5'] ?? '' !!}</p>

				</div>
			</div>
		</div>
	</div>
	<section class="section service gray-bg">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-7 text-center">
					<div class="section-title">
						<h2> شروط الأستخدام</h2>
						<div class="divider mx-auto my-4"></div>
						<p>{!! $hospital['cond_card'] ?? '' !!}</p>
		
	
					</div>
				</div>
			</div>
		</div>
	</section>

</section>
<!-- footer Start -->
<footer class="footer section gray-bg">
	<div class="container">
	
		<div class="footer-btm py-4 mt-5">
			<div class="row align-items-center justify-content-between">
				<div class="col-lg-6">
					<div class="copyright" style="text-align: right">
						&copy; 2023	جميع الحقوق محفوظة  <a href="https://d-targetco.net/">شركة الهدف المباشر</a>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="copyright">
						
						تم التطوير من قبل 
						<a href="https://intellijapp.github.io/">intellijapp</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<a class="backtop scroll-top-to" href="#top">
						<i class="icofont-long-arrow-up"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</footer>

   

    <!-- 
    Essential Scripts
    =====================================-->
    <script src="par/plugins/jquery/jquery.js"></script>
    <script src="par/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="par/plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="par/plugins/shuffle/shuffle.min.js"></script>


    <script src="par/js/script.js"></script>

  </body>
  </html>


