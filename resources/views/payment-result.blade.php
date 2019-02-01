<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>تایید پرداخت وجه</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet"/>
    <link href="{{asset('bootstrap/bootstrap.css')}}" rel="stylesheet"/>
    <link href="{{asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <style>
        body {
            background-color: #5ce5aa;
            background: linear-gradient(to bottom right, #5ce5aa, #f197a2) no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body dir="rtl">
<div class="container mt-5 pt-5">
    <div class="row mt-5 pt-5">
        <div class="col-lg-9 col-sm-12 m-auto text-center pt-3">
            @if($status == 'success')
                <p style="font-size: x-large;">
                    با تشکر از شما خیر گرامی، موجودی حساب مهربانی به <b>{{$balance}}تومان </b>افزایش پیدا کرد.
                </p>
                <p class="badge pt-4" style="font-size: large; color: #4360a8;">
                    حالا افراد بیشتری می توانند به آرزوهایشان برسند.
                </p>
            @else
                <p>{{$catchMessage}}</p>
            @endif
            <button class="btn btn-primary">بازگشت به ربات</button>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('bootstrap/bootstrap.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.btn').click(function () {
            window.location.href = 'https://t.me/{{$username}}'
        })
    });
</script>
</body>
</html>
