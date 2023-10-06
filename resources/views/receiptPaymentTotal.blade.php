<!DOCTYPE html>
<html>
<head>
    <title>شركة الهدف المباشر</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body style="direction: rtl;">
<div class="container">       
<div class="row">
    <div class="col-4 text-center py-3 ">
        <h6 class="pt-3">
       {{$config['first_title_ar']}}
        </h6>
        <h6>
        {{$config['second_title_ar']}}
        </h6>
    </div>
    <div class="col-4 text-center py-3">

    
       
    <h5 class="pt-3">   قسم المحاسبة </h5>
    </div>
    <div class="col-4 text-center py-3"> 
        @include('Components.logo')

    </div>
    </div>
    <div class="row p-2 text-center border-top border-bottom" style="font-size: 14px">
    @if($_GET['from'] ??'')
    <div class="col">
    من تاريخ:
    <?= $_GET['from'] ??'' ?>
    </div>
    @endif
    @if($_GET['to'] ??'')
    <div class="col">
        حتى تاريخ:
    <?= $_GET['to'] ??'' ?>
    </div>
    @endif
  </div>
  <div class="row p-2 text-center border-bottom alert-primary "  style="font-size: 14px">
    <div class="col-3"> 
      حساب الصندوق:
    {{$data['sum_transactions']}}
    </div>
    <div class="col-3">
      مسحوبات الصندوق:
    {{$data['sum_transactions_debit']}}
    </div>
    <div class="col-3">
      دخل الصندوق:
     {{$data['sum_transactions_in']}}
    </div>
    <div class="col-3">
      رصيد الصندوق:
    {{$data['user']['wallet']->balance}}
    </div>
  </div>
  <div class="row text-center py-2">
    <table class="table table-sm table-striped table-bordered" style="font-size: 12px">
        <thead>
          <tr>
            <th scope="col">رقم الوصل</th>
            <th scope="col">تاريخ</th>
            <th scope="col">ملاحظة</th>
            <th scope="col">المبلغ</th>
            
          </tr>
        </thead>
        <tbody>
            @foreach ($data['transactions'] as $key=>$data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->created }}</td>
                <td>{{ $data->description }}</td>
                <td>{{ $data->amount  }}</td>
            </tr>
            @endforeach
        </tbody>
      </table>  
  </div>
</div>


<script>
    $(document).ready(function() {
        // Function to open the print dialog
        function openPrintDialog() {
           // window.print();
        }
    
        // Call the function to open the print dialog
        openPrintDialog();
    });
    </script>

</body>
</html>
