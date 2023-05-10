<!DOCTYPE html>
<html>
<head>
    <title>دائرة صحة محافظة كركوك</title>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <style type="text/css">
        .col-4{
            font-size: 16px;font-weight: 700;padding-right: 20px;
        }
        .red{
            color:rgb(255, 8, 78)
        }
</style>   
</head>
<body style=" direction:rtl;margin-top:-20px;padding: 0;margin:0 ">
<div style="background-color: black;padding:2px;margin: -5px;">
<div  style="background-color: #fff;padding:2px; padding-bottom:10px;">
<table>
<tr>
    <th style=" font-size: 16px;font-weight: 700;padding-right: 20px">
        وزارة الصحة العراقية <br>
        {{$config['first_title_ar']}}
        <br>
        {{$config['second_title_ar']}}
        <br>
        {{$config['third_title_ar']}}
        <br>
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px">
    @include('logo')
    
       <br> استمارة فحص المقبلين على الزواج
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px"> 
        وەزارەتی تەندروستی عێراق<br>
        {{$config['first_title_kr']}}
        <br>
        {{$config['second_title_kr']}}
        <br>
        {{$config['third_title_kr']}}
        <br>
    </th>
    </tr>
    <tr>
        <td style="padding-right:20px;font-size:13px">
        الرقم:
        <span>{{$profile->no}}</span>
        </td>
    </tr>
    <tr>
        <td style="padding-right:20px;font-size:13px">
        بتاريخ:
        <span>{{$profile->created_at}}</span>
        </td>
    </tr>
    <tr>
        <td style="padding-right:20px;font-size:13px">
        رقم الوصل:
            <span>{{$profile->invoice_number}}</span>
        </td>
    </tr>
</table>
<hr style="width: 500px;margin: 0;margin: 5px 0px">
<table style="width: 100%;">
<tr>
    <th  style="font-weight: 700;font-size:16px;color:rgb(255, 8, 78);text-align: right;padding-right: 20px">
    بيانات الزوج

    </th>
    <th   style="font-weight: 700;font-size:16px;color:rgb(255, 8, 78);text-align: right;padding-right: 10px">
    بيانات الزوجة

    </th>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <th  style="font-weight: 700;font-size:13px;text-align: right;  line-height: 20px;padding-right: 20px">
            الاسم:   {{ $profile->husband_name }}
            <br>
            التولد:   {{ $profile->husband_birthdate  }}
            <br>
            التحصيل الدراسي:   {{ $profile->husband_certification }}
            <br>
            المهنة:   {{ $profile->husband_job }}
            <br>
            العنوان:   {{ $profile->husband_address }}    
        </th>
        <th >
            <img src="{{$profile->husband_image}}" width="100px"/>
        </th>
        <th   style="font-weight: 700;font-size:13px;text-align: right; line-height: 20px; padding-right:10px ;">
            الاسم:   {{ $profile->wife_name }}
            <br>
            التولد:   {{ $profile->wife_birthdate }}
            <br>
            التحصيل الدراسي:   {{ $profile->wife_certification }}
            <br>
            المهنة:   {{ $profile->wife_job }}
            <br>
            العنوان:   {{ $profile->wife_address }}    
            <br>
            

        </th>
        <th >
            <img src="{{$profile->wife_image}}" style="padding-left: 10px" width="100px" />
        </th>
        </tr>
</table>


<table style="width: 100%;">
    <tr>
        <th  style="font-weight: 700;font-size:13px;text-align: right;  line-height: 25px;padding: 0 100px;">
            الهاتف:   {{ $profile->phone_number }} 
        </th>
 
        <th   style="font-weight: 700;font-size:13px;text-align: right; line-height: 25px;padding: 0 100px;">
            درجة القرابة:   {{ $profile->relatives }} 
        </th>
        </tr>
</table>
<hr style="width: 500px;margin: 0 ;padding:0">
<h3 style="text-align: center;color:rgb(255, 8, 78);font-weight: 700;font-size:15px">التاريخ الصحي العائلي - امراض الدم الوراثية
</h3>
<table style="width: 100%;">
    <tr>
        <th style="text-align: right;font-size:13px;padding-right: 20px;font-weight: 500">الثلاسيميا :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 50px<?php  if($resultsDoctor->husband_talasyma){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->husband_talasyma ? 'نعم' :'كلا'}}</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 10px">الثلاسيميا :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 40px<?php  if($resultsDoctor->wife_talasyma){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->wife_talasyma  ? 'نعم' :'كلا'}}</th>
    </tr>
    <tr>
        <th style="text-align: right;font-size:13px;padding-right: 20px;font-weight: 500">فقر الدم المنجلي :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 50px<?php  if($resultsDoctor->husband_faqar){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->husband_faqar  ? 'نعم' :'كلا'}}</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 15px">فقر الدم المنجلي :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 40px;<?php  if($resultsDoctor->wife_faqar){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->wife_faqar   ? 'نعم' :'كلا'}}</th>
    </tr>
    <tr>
        <th style="text-align: right;font-size:13px;padding-right: 20px;font-weight: 500">الهيموفيليا :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 50px<?php  if($resultsDoctor->husband_himofilya){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->husband_himofilya  ? 'نعم' :'كلا'}}</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 15px">الهيموفيليا :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 40px<?php  if($resultsDoctor->wife_himofilya){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->wife_himofilya  ? 'نعم' :'كلا'}}</th>
    </tr>
    <tr>
        <th style="text-align: right;font-size:13px;padding-right: 20px;font-weight: 500">العوق الذهني :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 50px<?php  if($resultsDoctor->husband_al){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->husband_al ? 'نعم' :'كلا'}}</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 15px">العوق الذهني :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 40px<?php  if($resultsDoctor->wife_al){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->wife_al  ? 'نعم' :'كلا'}}</th>
    </tr>
    <tr>
        <th style="text-align: right;font-size:13px;padding-right: 20px;font-weight: 500">داء السكري :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 50px<?php  if($resultsDoctor->husband_dam){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->husband_dam ? 'نعم' :'كلا'}}</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 15px">داء السكري :</th>
        <th style="text-align: right;font-size:13px;font-weight: 500;padding-left: 40px<?php  if($resultsDoctor->wife_dam){echo 'color:rgb(255, 8, 78)';}?>">{{ $resultsDoctor->wife_dam  ? 'نعم' :'كلا'}}</th>
    </tr>
</table>
<hr style="width:500px;margin: 0;">
<h3 style="text-align: center;color:rgb(255, 8, 78);font-weight: 700;font-size:16px">الفحوصات المختبرية

</h3>
<table style="width: 100%;direction: ltr !important">
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> B-group & RH :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;">{{ $results->wife_b}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">B-group & RH :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px">{{ $results->husband_b}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500">Hb (g\L) (11-15) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hb < 11 || $results->wife_hb > 15){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hb}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">Hb (g\L) (11-15) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hb < 11 || $results->husband_hb > 15){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hb}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> MCV (83-101) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_mcv < 83 || $results->wife_mcv > 101){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_mcv}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">MCV (83-101) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_mcv < 83 || $results->husband_mcv > 101){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_mcv}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> MCH (27-32) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_mch < 27 || $results->wife_mch > 32){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_mch}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">MCH (27-32) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_mch < 27 || $results->husband_mch > 32){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_mch}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> Hemoglobin F (0.8-2) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hemoglobin_f < 0.8 || $results->wife_hemoglobin_f > 2){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hemoglobin_f}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">Hemoglobin F (0.8-2) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hemoglobin_f < 0.8 || $results->husband_hemoglobin_f > 2){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hemoglobin_f}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500">  Hemoglobin A2 (1.5-3.5) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hemoglobin_a2 < 1.5 || $results->wife_hemoglobin_a2 > 3.5){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hemoglobin_a2}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500"> Hemoglobin A2 (1.5-3.5) :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hemoglobin_a2 < 1.5 || $results->husband_hemoglobin_a2 > 3.5){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hemoglobin_a2}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> HBs Ag :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hbs == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hbs}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">HBs Ag :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hbs == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hbs}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> HCV :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hcv == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hcv}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">HCV :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hcv == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hcv}}</th>
    </tr>
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> HIV :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_hiv == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_hiv}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">HIV :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_hiv == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_hiv}}</th>
    </tr>
    @if(($results->wife_tb ?? '')&&($results->husband_tb ?? ''))
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> TB :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_tb == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_tb}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">TB :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_tb == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_tb}}</th>
    </tr>
    @endif
    <tr>
        <th style="text-align: left;font-size:13px;padding-left: 20px;font-weight: 500"> Syphilis :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 50px;<?php  if($results->wife_syphilis == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->wife_syphilis}}</th>
        <th style="text-align: left;font-size:13px;font-weight: 500">Syphilis :</th>
        <th style="text-align: left;font-size:13px;font-weight: 500;padding-right: 40px;<?php  if($results->husband_syphilis == 'positive'){echo 'color:rgb(255, 8, 78)';}?>">{{ $results->husband_syphilis}}</th>
    </tr>
</table>
<hr style="width: 500px;margin: 5px 0;">
<div style="font-weight: 700;font-size:14px;padding-right: 20px">وتبين بعد الفحوصات السريرية ما يلي:</div>
<div style="width: 100%;padding-right: 20px">
        <div  style="font-weight: 700;font-size:14px;">
            <span>الزوج:  {{ $resultsDoctor->husband_results ==0 ? "" : "سليم,"}}
                {{ $resultsDoctor->husband_note }}</span>
        </div>
 
        <div   style="font-weight: 700;font-size:14px;">
            <span>الزوجة:{{ $resultsDoctor->wife_results ==0 ? "" : "سليمة,"}}
                {{ $resultsDoctor->wife_note}}</span>
        </div>
</div>
<table style="width: 100%;margin-top:0">
    <tr>
        <th  style="font-weight: 700;font-size:14px;text-align: right;padding-right: 20px">
            توقيع الزوج  
        </th>
 
        <th   style="font-weight: 700;font-size:14px;text-align: right;padding-right: 12px">
             توقيع الزوجة
        </th>
        </tr>
</table>

<table style="width: 100%; margin-top: 0px;">
    <tr>
        <th  style="font-weight: 700;font-size:14px;text-align: center;padding: 0 25px;">
              اسم وتوقيع الطبيب الفاحص 
        </th>
 
        <th   style="font-weight: 700;font-size:14px;text-align: center;padding: 0 25px;">
              ختم المؤسسة الصحية 
        </th>
        <th   style="font-weight: 700;font-size:14px;text-align: center;padding: 0 25px;">
              اسم وتوقيع مدير المختبر 
        </th>
        <th>
            <div style="color: #fff;text-align: left;padding-left: 20px;">
                {!! QrCode::size(100)->generate($url.'/show/'.$profile->id); !!}
            </div>
        </th>
        </tr>
</table>
</div>
</div>
</body>
</html>