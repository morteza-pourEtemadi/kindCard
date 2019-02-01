<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ورود</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet"/>
    <link href="{{asset('bootstrap/bootstrap.css')}}" rel="stylesheet"/>
    <link href="{{asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"/>
</head>
<body dir="rtl">
<div class="container-fluid">
    <div class="row">
        <div class="col-5">
        </div>
        <div class="col-3">
            <form method="post" target="">
                {{csrf_field()}}
                <br><label for="username">نام کاربری</label>
                <input type="text" id="username" name="username">
                <br><label for="password">رمز عبور &nbsp;</label>
                <input type="password" id="password" name="password">
                <div class="col-12 text-center">
                    <br>
                    <button type="submit" class="btn btn-success" id="submit">ورود</button>
                </div>
            </form>
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
