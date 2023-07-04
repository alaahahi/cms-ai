<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الحجوزات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
<body  dir="rtl">
    <h3 class="text-center py-3">حجوزات الطبيب</h3>
    <table class="table table-striped table-hover">
<tr class="text-center">
    <th style=" font-size: 16px;font-weight: 700;padding-right: 20px">
    الطبيب
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px">
    مجموع البطاقات
    </th>

</tr>
    @foreach($doctor as $data)
    <tr class="text-center">
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->user->name}}</span>
            </td>

            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->count}}</span>
            </td>

    </tr>
    @endforeach
</table>

<table class="table table-striped table-hover">
<tr class="text-center">
    <th style=" font-size: 16px;font-weight: 700;padding-right: 20px">
    الطبيب
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px">
   بطاقة رقم
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px"> 
     اسم الزبون
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px"> 
     هاتف
    </th>
    <th  style=" font-size: 16px;font-weight: 700;padding-right: 45px"> 
     ملاحظة
    </th>
</tr>
    @foreach($appointment as $data)
    <tr class="text-center">
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->user?->name}}</span>
            </td>

            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->card_id}}</span>
            </td>
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->profile?->name}}</span>
            </td>
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->profile?->phone_number}}</span>
            </td>

            <td style="padding-right:20px;font-size:13px">
                <span>{{$data->note}}</span>
            </td>
    </tr>
    @endforeach
</table>
</body>
</html>