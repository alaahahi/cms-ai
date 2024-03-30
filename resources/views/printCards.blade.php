<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بطاقات {{$config['third_title_ar']}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
<body  dir="rtl">
    <h3 class="text-center py-2">بطاقات {{$config['third_title_ar']}}</h3>
    <h4 class="text-center py-2 mb-4">
        من تاريخ 
        {{$from}}
        الى 
        {{$to}}
    </h4>

<table class="table table-striped table-hover">
<tr class="text-center">
    <th style=" font-size: 16px;font-weight: 700;padding-right: 20px">
    تسلسل
    </th>
    <th style=" font-size: 16px;font-weight: 700;padding-right: 20px">
    المندوب
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
    <?php $i = 1 ?>
    @foreach($data as $data)
    <tr class="text-center">
            <td style="padding-right:20px;font-size:13px">
            <span>{{$i}}</span>
            </td>
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->user?->name}}</span>
            </td>

            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->card_number}}</span>
            </td>
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->name}}</span>
            </td>
            <td style="padding-right:20px;font-size:13px">
            <span>{{$data->phone_number}}</span>
            </td>

            <td style="padding-right:20px;font-size:13px">
                <a href="https://{{$hospital->subdomain}}.intellijapp.com/public/uploads/{{$data->image}}">{{$data->image}}</a>
            </td>
    </tr>
    <?php $i++ ?>

    @endforeach
</table>
</body>
</html>