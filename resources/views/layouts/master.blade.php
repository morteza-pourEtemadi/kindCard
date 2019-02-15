<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{asset('theme/img/favicon.png')}}" type="image/png">
    <title>@yield('title') | حساب مهربانی</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('theme/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('theme/vendors/linericon/style.css')}}">
    <link rel="stylesheet" href="{{asset('theme/vendors/lightbox/simpleLightbox.css')}}">
    <link rel="stylesheet" href="{{asset('theme/vendors/nice-select/css/nice-select.css')}}">
    <!-- main css -->
    <link rel="stylesheet" href="{{asset('theme/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @yield('styles')
</head>
<body>
@include('layouts.navbar')
@yield('content')
@include('layouts.footer')

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript" src="{{asset('theme/js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/js/popper.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('bootstrap/bootstrap-notify.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/js/stellar.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/vendors/lightbox/simpleLightbox.min.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/vendors/nice-select/js/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('theme/js/theme.js')}}"></script>
<script type="text/javascript">
    function startLoading(elem) {
        $('.loading').css({
            'display': 'block'
        });

        elem = elem || 0;
        if (elem) {
            elem.attr('disabled', 'disabled');
        }
    }

    function stopLoading(elem) {
        $('.loading').css({
            'display': 'none'
        });

        elem = elem || 0;
        if (elem) {
            elem.removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        @for($i = 0; $i < 6; $i++)
        $({countNum: 0}).animate({countNum: $('#count-{{$i}}').data('val')}, {
            duration: 5000,
            easing: 'linear',
            step: function () {
                $('#count-{{$i}}').text(Math.floor(this.countNum));
            },
            complete: function () {
                $('#count-{{$i}}').text(this.countNum);
            }
        });
        $({countNum: 0}).animate({countNum: $('#balance-{{$i}}').data('val')}, {
            duration: 5000,
            easing: 'linear',
            step: function () {
                $('#balance-{{$i}}').text(Math.floor(this.countNum) + ' تومان');
            },
            complete: function () {
                $('#balance-{{$i}}').text(this.countNum + ' تومان');
            }
        });
        @endfor

        $('a[href*="#"]').not('[href="#"]').click(function (event) {
            if (
                location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '')
                &&
                location.hostname === this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function () {
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr('tabindex', '-1');
                            $target.focus();
                        }
                    });
                }
            }
        });

        $('.sub-btn').click(function () {
            var val = $('#sub-email').val();
            if (val === undefined || val == null || val === '') {
                return;
            }

            $.ajax({
                method: 'POST',
                url: '{{route('join.newsletter')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    email: val
                },
            }).done(function (response) {
                if (response.type === 'success') {
                    $('#sub-email').val('').text('');
                }
                $.notify({
                    message: response.message
                }, {
                    z_index: 1000000,
                    type: response.type
                });
            });
        })
    })
</script>
@yield('scripts')
</body>
</html>