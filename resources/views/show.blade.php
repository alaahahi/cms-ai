<!DOCTYPE html>
<html>
<head>
    <title>دائرة صحة محافظة كركوك</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
</head>
<body style="direction: rtl;">
<div class="container">       
<div class="row">
    <div class="col-4 text-center py-4">
        <div>
        وزارة الصحة العراقية 
        </div>
        <div>
       {{$config['first_title_ar']}}
        </div>
        <div>
        {{$config['second_title_ar']}}
        </div>
        <div>
        {{$config['third_title_ar']}}
        </div>
    </div>
    <div class="col-4 text-center py-4">

    @include('logo')
    
       
    <p> استمارة فحص المقبلين على الزواج </p>
    </div>
    <div class="col-4 text-center py-4"> 
        <div>
        وەزارەتی تەندروستی عێراق
        </div>
        <div>
       {{$config['first_title_kr']}}
        </div>
        <div>
        {{$config['second_title_kr']}}
        </div>
        <div>
        {{$config['third_title_kr']}}
        </div>
    </div>
    </div>
    <div class="row pb-3 text-center" >
    <div class="col-4"> 
    الرقم:
    <span>{{$profile->no}}</span>
    </div>
    <div class="col-4">
    التاريخ:
    <span style="direction: ltr;">{{$profile->created_at}}</span>
    </div>
    <div class="col-4">
    رقم الوصل:
        <span>{{$profile->invoice_number}}</span>
    </div>
  </div>
  <div class="row text-center">
    <div class="col-6">
        <h4 class="text-primary">بيانات الزوج</h4>
    </div>
    <div class="col-6">
        <h4 class="text-primary">بيانات الزوجة</h4>
    </div>
  </div>
  <div class="row text-center py-4">
    <div class="col-6">
        <img class="rounded-3" src="{{$profile->husband_image}}" width="200px"/>
    </div>
    <div class="col-6">
        <img class="rounded-3" src="{{$profile->wife_image}}" width="200px" />
    </div>
  </div>
  <div class="row text-center py-2">
    <div class="col-6">
            <div>
            الاسم:   {{ $profile->husband_name }}
            </div>
            <div>
            التولد:   {{ $profile->husband_birthdate }}
            </div>
            <div>
            التحصيل الدراسي:   {{ $profile->husband_certification }}
            </div>
            <div>
            المهنة:   {{ $profile->husband_job }}
            </div>
            <div>
            العنوان:   {{ $profile->husband_address }}    
            </div>
    </div>
   
    <div class="col-6">
            <div >
            الاسم:   {{ $profile->wife_name }}
            </div>
            <div>
            التولد:   {{ $profile->wife_birthdate }}
            </div>
            <div>
            التحصيل الدراسي:   {{ $profile->wife_certification }}
            </div>
            <div>
            المهنة:   {{ $profile->wife_job }}
            </div>
            <div>
            العنوان:   {{ $profile->wife_address }}    
        </div>

    </div>    
  </div>

  <div class="row text-center py-2">
    <div class="col-6">
        <div class="fw-bold" >
            الهاتف:   {{ $profile->phone_number }} 
        </div>
    </div>
   
    <div class="col-6">
            <div class="fw-bold">
                درجة القرابة:   {{ $profile->relatives }} 
            </div>
           
    </div>    
  </div>
  @if(isset($resultsDoctor))
  <div class="row text-center">
    <h4 class="text-primary">التاريخ الصحي العائلي - امراض الدم الوراثية
    </h4>
  </div>
  <div class="row text-center py-2">
    <div class="col-6">
            <div>
                الثلاسيميا:   {{ $resultsDoctor->husband_talasyma ? 'نعم' :'كلا'}}
            </div>
            <div>
                فقر الدم المنجلي:   {{ $resultsDoctor->husband_faqar ? 'نعم' :'كلا'}}
            </div>
            <div>
                الهيموفيليا:   {{ $resultsDoctor->husband_himofilya ? 'نعم' :'كلا'}}
            </div>
            <div>
                العوق الذهني:   {{ $resultsDoctor->husband_al ? 'نعم' :'كلا'}}
            </div>
            <div>
                داء السكري:   {{ $resultsDoctor->husband_dam ? 'نعم' :'كلا' }}   
            </div>
    </div>
   
    <div class="col-6">
            <div >
                الثلاسيميا:   {{ $resultsDoctor->wife_talasyma  ? 'نعم' :'كلا'}}
            </div>
            <div>
                فقر الدم المنجلي:   {{ $resultsDoctor->wife_faqar ? 'نعم' :'كلا' }}
            </div>
            <div>
                الهيموفيليا:   {{ $resultsDoctor->wife_himofilya ? 'نعم' :'كلا'}}
            </div>
            <div>
                العوق الذهني:   {{ $resultsDoctor->wife_al ? 'نعم' :'كلا'}}
            </div>
            <div>
                داء السكري:   {{ $resultsDoctor->wife_dam ? 'نعم' :'كلا'}}  
        </div>

    </div>    
  </div>
  @endif
  @if(isset($results))
  <div class="row text-center">
    <h4 class="text-primary">الفحوصات المختبرية
    </h4>
  </div>
  <div class="row text-center py-2">
    <div class="col-6">
            <div>
                B-group & RH            :{{ $results->husband_b}}
            </div>
            <div>
                Hb (g\L) (11-15)            :{{ $results->husband_hb}}
            </div>
            <div>
                MCV (83-101)         :{{ $results->husband_mcv}}
            </div>
            <div>
                MCH (27-32)         :{{ $results->husband_mch}}
            </div>
      
            <div>
                Hemoglobin F (0.8-2)            :{{ $results->husband_hemoglobin_f}}
            </div>
            <div>
                Hemoglobin A2 (1.5-3.5)           :{{ $results->husband_hemoglobin_a2}}
            </div>
            <div>
                HBs Ag          :{{ $results->husband_hbs}}
            </div>
            <div>
                HCV         :{{ $results->husband_hcv}}
            </div>
            <div>
                HIV         :{{ $results->husband_hiv}}
            </div>
            @if($results->husband_tb ?? '')
            <div>
                TB          :{{ $results->husband_tb}}
            </div>
            @endif
            <div>
                Syphilis            :{{ $results->husband_syphilis}}
            </div>
    </div>
   
    <div class="col-6">
        <div>
            B-group & RH            :{{ $results->wife_b}}
        </div>
        <div>
            Hb (g\L) (11-15)            :{{ $results->wife_hb}}
        </div>
        <div>
            MCV (83-101)         :{{ $results->wife_mcv}}
        </div>
        <div>
            MCH (27-32)         :{{ $results->wife_mch}}
        </div>
    
        <div>
            Hemoglobin F (0.8-2)            :{{ $results->wife_hemoglobin_f}}
        </div>
        <div>
            Hemoglobin A2 (1.5-3.5)           :{{ $results->wife_hemoglobin_a2}}
        </div>
        <div>
            HBs Ag          :{{ $results->wife_hbs}}
        </div>
        <div>
            HCV         :{{ $results->wife_hcv}}
        </div>
        <div>
            HIV         :{{ $results->husband_hiv}}
        </div>
        @if($results->wife_tb ?? '')
        <div>
            TB          :{{ $results->wife_tb}}
        </div>
        @endif
        <div>
            Syphilis            :{{ $results->wife_syphilis}}
        </div>
    </div>    
  </div>
  <div class="row text-center">
    <h5 class="text-primary">وتبين بعد الفحوصات السريرية ما يلي
    </h5>
  </div>
@endif
@if(isset($resultsDoctor))
  <div class="row text-center py-2">
    <div class="col-6">
        <div class="fw-bold" >
        <span>الزوج:  {{ $resultsDoctor->husband_results ==0 ? "" : "سليم,"}}
                {{ $resultsDoctor->husband_note }}</span>
        </div>
    </div>
   
    <div class="col-6">
            <div class="fw-bold">
            <span>الزوجة:{{ $resultsDoctor->wife_results ==0 ? "" : "سليمة,"}}
                {{ $resultsDoctor->wife_note}}</span> 
            </div>
           
    </div>    
  </div>
  @endif
</div>
</body>
</html>
