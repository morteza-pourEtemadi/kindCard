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
                <input type="text" id="username" name="username" required="required">
                <br><label for="password">رمز عبور &nbsp;</label>
                <input type="password" id="password" name="password" required="required">
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
</body>
</html>
