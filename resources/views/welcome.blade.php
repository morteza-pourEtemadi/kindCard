<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{asset('theme/img/favicon.png')}}" type="image/png">
    <title>حساب مهربانی</title>
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
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
</head>
<body>

<!--================ Start Header Menu Area =================-->
<header class="header_area">
    <div class="main_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="{{route('index')}}"><img src="{{asset('theme/img/logo.png')}}"
                                                                                  alt="Logo"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto" dir="rtl">
                            <li class="nav-item active"><a class="nav-link" href="{{route('index')}}">خانه</a></li>
                            <li class="nav-item"><a class="nav-link" href="#about">درباره حساب مهربانی</a></li>
                            <li class="nav-item"><a class="nav-link" href="#causes">دلایل؟</a></li>
                            <li class="nav-item"><a class="nav-link" href="#terms">قوانین و مقررات</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contact">تماس با ما</a></li>
                            <li class="nav-item"><a class="nav-link" href=""></a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<!--================ End Header Menu Area =================-->

<!--================ Home Banner Area =================-->
<section class="home_banner_area">
    <div class="banner_inner">
        <div class="container">
            <div class="banner_content">
                <p class="upper_text">دست به دست هم دهیم به مهر</p>
                <h3 class="text-white">ميهن خويش را کنيم آباد</h3>
                <pre class="text-white f-500">يار و غم‌خوار همدگر باشيم        تا بـمـانـيـم خرم و آزاد</pre>
                <br>
                <a class="primary_btn mr-20" href="">اکنون کمک کنید</a>
                <a class="primary_btn yellow_btn text-white" href="#causes">نیاز به دلیل دارید؟</a>
            </div>
        </div>
    </div>
</section>
<!--================ End Home Banner Area =================-->

<!--================ Start Causes Area =================-->
<section class="features_causes mt-5 pt-5 pb-5 px-0" id="about">
    <div class="container pt-5">
        <div class="main_title mb-3">
            <h2>این حساب چجوری کار می کنه؟</h2>
            <br><br>
            <h5>موجودی فعلی حساب: {{$balance}} تومان</h5>
        </div>
        <div class="row justify-content-center" dir="rtl">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <figure>
                            <img class="card-img-top img-fluid"
                                 src="{{asset('theme/img/give.png')}}"
                                 alt="Card image cap">
                        </figure>
                        <div class="card_inner_body text-center">
                            <h4 class="card-title">نیاز نداری بذار</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <figure>
                            <img class="card-img-top img-fluid"
                                 src="{{asset('theme/img/get.png')}}"
                                 alt="Card image cap">
                        </figure>
                        <div class="card_inner_body text-center">
                            <h4 class="card-title">نیاز داری بردار</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center pb-3">
            <h3>به همین راحتی</h3>
            <p>
                متن درباره حساب مهربانی
            </p>
        </div>
    </div>
</section>
<!--================ End Causes Area =================-->
<hr class="fancy-hr">
<!--================ Start Causes Area =================-->
<section class="features_causes mt-5 pt-5 pb-5 mb-5 px-0" id="causes">
    <div class="container pt-5">
        <div class="main_title">
            <h2>دلایل اصلی ما</h2>
            <p></p>
        </div>
        <div class="row" dir="rtl">
            <?php
            $i = 0;
            $titles = [
                'تهیه غذا',
                'کمک های پزشکی و دارویی',
                'تهیه پوشاک',
                'تامین حداقل مسکن',
                'تحصیل برای همه',
                'تهیه حداقل جهیزیه',
            ];
            $texts = [
                '',
                '',
                '',
                '',
                'امکان خرید برای هر کودک این سرزمین',
                '',
            ];
            ?>
            @foreach($categories as $category)
                @if($i > 5)
                    @continue
                @endif
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <figure>
                                <img class="card-img-top img-fluid" style="max-height: 231px;"
                                     src="{{asset('theme/img/causes/c' . $i . '.png')}}"
                                     alt="Card image cap">
                            </figure>
                            <div class="card_inner_body text-right">
                                <h4 class="card-title">{{$titles[$i]}}</h4>
                                <p class="card-text">
                                    {{$texts[$i]}}
                                </p>
                                <div class="py-1">
                                    <div class="my-3" style="min-height: 30px;">
                                        <div class="text-right float-right">
                                            <span class="label label-primary">تعداد کمک ها:
                                                <span id="count-{{$i}}"
                                                      data-val="{{$category['count']}}">0</span>
                                            </span>
                                        </div>
                                        <div class="text-left float-left">
                                            <span class="label label-primary">مبلغ هزینه شده:
                                                <span id="balance-{{$i}}"
                                                      data-val="{{$category['balance']}}">0 تومان</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between donation align-items-center">
                                    @if($i < count($categories) - 1)
                                        <a href="" class="primary_btn">کمک کنید</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++ ?>
            @endforeach
        </div>
    </div>
</section>
<!--================ End Causes Area =================-->
<hr class="fancy-hr" id="terms">
<!--================ Start Recent Event Area =================-->
<section class="event_area section_gap_custom mt-5 pt-5 pb-5 px-0">
    <div class="container mt-5 pt-5">
        <div class="main_title mb-5">
            <h2>قوانین و مقررات این حساب</h2>
            {{--<p></p>--}}
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="single_event">
                    <div class="content_wrapper text-right" dir="rtl">
                        <ol class="terms">
                            <li>در راستای سرعت بخشیدن به عملیات کمک‌رسانی، ما بنا را بر اصل صداقت و راستگویی گذاشته و
                                خواهشمندیم که در صورت وجود نیاز واقعی، از منابع این حساب استفاده نمایید.
                            </li>
                            <li>حساب مهربانی حق خود می‌داند که برای برداشت مبالغ بالای 1 میلیون تومان، بررسی‌های لازم را
                                درباره‌ی مورد استفاده‌ی وجه انجام دهد.
                            </li>
                            <li>حداقل زمان بین ثبت درخواست و واریز وجوه، 2 روز و حداکثر 4 روز کاری می‌باشد.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Recent Event Area =================-->

<!--================Team Area =================-->
<section class="team_area section_gap mt-5 pt-5" id="contact">
    <div class="container py-5">
        <div class="main_title">
            <h2>تماس با ما</h2>
            {{--<p></p>--}}
        </div>
        <div class="row team_inner pb-5">
            <div class="col-lg-4 col-md-12 mb-5 mb-lg-0 col-sm-12 text-center">
                <a class="contact" id="telegram" href="https://t.me/kind_card_bot" target="_blank">
                    <i class="fa fa-telegram fa-5x"></i>
                    <span>@kind_card_bot</span>
                </a>
            </div>
            <br><br>
            <div class="col-lg-4 col-md-12 mb-5 mb-lg-0 col-sm-12 text-center">
                <a class="contact" id="mail" href="mailto:admin@kindcard.ir" target="_blank">
                    <i class="fa fa-envelope-o fa-5x"></i>
                    <span>admin@kindcard.ir</span>
                </a>
            </div>
            <div class="col-lg-4 col-md-12 mb-5 mb-lg-0 col-sm-12 text-center">
                <a class="contact" id="text" href="sms:+989336365162" target="_blank">
                    <i class="fa fa-comments-o fa-5x"></i>
                    <span>0933 6365 162</span>
                </a>
            </div>
        </div>
    </div>
</section>
<!--================End Team Area =================-->

<!--================ Start CTA Area =================-->
<div class="cta-area section_gap overlay">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" dir="rtl">
                <h1>شما هم حامی هستید؟</h1>
                <p>
                    می‌گویند که بال زدن یک پروانه در صحرای آفریقا می‌تواند طوفانی در آمریکا به پا کند.<br>
                    به یک تغییر بیندیشید و عزم خود را جمع کنید. شاید کمک‌های به ظاهر کوچک هم بتواند تغییری بزرگ ایجاد
                    کند.
                </p>
                <a href="#" class="primary_btn yellow_btn rounded">به جمع حامیان بپیوندید</a>
            </div>
        </div>
    </div>
</div>
<!--================ End CTA Area =================-->

<!--================ Start Story Area =================-->
<section class="section_gap story_area">
{{--<div class="container">--}}
{{--<div class="row justify-content-center">--}}
{{--<div class="col-lg-7">--}}
{{--<div class="main_title">--}}
{{--<h2>آخرین داستان‌های ما</h2>--}}
{{--<p></p>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="row">--}}
<!-- single-story -->
{{--<div class="col-lg-4 col-md-6">--}}
{{--<div class="single-story">--}}
{{--<div class="story-thumb">--}}
{{--<img class="img-fluid" src="theme/img/story/s1.jpg" alt="">--}}
{{--</div>--}}
{{--<div class="story-details">--}}
{{--<div class="story-meta">--}}
{{--<a href="#">--}}
{{--<span class="lnr lnr-calendar-full"></span>--}}
{{--20th Sep, 2018--}}
{{--</a>--}}
{{--<a href="#">--}}
{{--<span class="lnr lnr-book"></span>--}}
{{--Company--}}
{{--</a>--}}
{{--</div>--}}
{{--<h5>--}}
{{--<a href="#">--}}
{{--Lime recalls electric scooters over--}}
{{--battery fire.--}}
{{--</a>--}}
{{--</h5>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}

<!-- single-story -->
    {{--</div>--}}
    {{--</div>--}}
</section>
<!--================ End Story Area =================-->

<!--================ Start Subscribe Area =================-->
<div class="container">
    <div class="subscribe_area" dir="rtl">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center">
                    <h1 class="text-white">آیا مایلید از ما بیشتر بدانید؟</h1>
                    <div id="mc_embed_signup">
                        <form target="_blank"
                              action="https://kindcard.us20.list-manage.com/subscribe/post?u=b69469a39e542a58890b2bda5&amp;id=b0dab3bcd5"
                              method="post" class="subscribe_form relative">
                            <div class="input-group d-flex flex-row mr-20">
                                <input name="EMAIL" placeholder="آدرس ایمیل شما" onfocus="this.placeholder = ''"
                                       onblur="this.placeholder = 'آدرس ایمیل'" required="" type="email">
                                <button class="mr-20 primary_btn yellow_btn btn sub-btn rounded">اشتراک در خبرنامه
                                </button>
                            </div>
                            <div class="info"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--================ End Subscribe Area =================-->

<!--================ Start footer Area  =================-->
<footer>
    <div class="footer-area">
        <div class="container">
            {{--<div class="row section_gap">--}}
            {{--<div class="col-lg-3 col-md-6 col-sm-6">--}}
            {{--<div class="single-footer-widget tp_widgets">--}}
            {{--<h4 class="footer_title large_title">Our Mission</h4>--}}
            {{--<p>--}}
            {{--So seed seed green that winged cattle in. Gathering thing made fly you're no--}}
            {{--divided deep moved us lan Gathering thing us land years living.--}}
            {{--</p>--}}
            {{--<p>--}}
            {{--So seed seed green that winged cattle in. Gathering thing made fly you're no divided deep--}}
            {{--moved--}}
            {{--</p>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="offset-lg-1 col-lg-2 col-md-6 col-sm-6">--}}
            {{--<div class="single-footer-widget tp_widgets">--}}
            {{--<h4 class="footer_title">Quick Links</h4>--}}
            {{--<ul class="list">--}}
            {{--<li><a href="#">Home</a></li>--}}
            {{--<li><a href="#">About</a></li>--}}
            {{--<li><a href="#">Causes</a></li>--}}
            {{--<li><a href="#">Event</a></li>--}}
            {{--<li><a href="#">News</a></li>--}}
            {{--<li><a href="#">Contact</a></li>--}}
            {{--</ul>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-2 col-md-6 col-sm-6">--}}
            {{--<div class="single-footer-widget instafeed">--}}
            {{--<h4 class="footer_title">Gallery</h4>--}}
            {{--<ul class="list instafeed d-flex flex-wrap">--}}
            {{--<li><img src="theme/img/gallery/g1.jpg" alt=""></li>--}}
            {{--<li><img src="theme/img/gallery/g2.jpg" alt=""></li>--}}
            {{--<li><img src="theme/img/gallery/g3.jpg" alt=""></li>--}}
            {{--<li><img src="theme/img/gallery/g4.jpg" alt=""></li>--}}
            {{--<li><img src="theme/img/gallery/g5.jpg" alt=""></li>--}}
            {{--<li><img src="theme/img/gallery/g6.jpg" alt=""></li>--}}
            {{--</ul>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="offset-lg-1 col-lg-3 col-md-6 col-sm-6">--}}
            {{--<div class="single-footer-widget tp_widgets">--}}
            {{--<h4 class="footer_title">Contact Us</h4>--}}
            {{--<div class="ml-40">--}}
            {{--<p class="sm-head">--}}
            {{--<span class="fa fa-location-arrow"></span>--}}
            {{--Head Office--}}
            {{--</p>--}}
            {{--<p>123, Main Street, Your City</p>--}}

            {{--<p class="sm-head">--}}
            {{--<span class="fa fa-phone"></span>--}}
            {{--Phone Number--}}
            {{--</p>--}}
            {{--<p>--}}
            {{--+123 456 7890 <br>--}}
            {{--+123 456 7890--}}
            {{--</p>--}}

            {{--<p class="sm-head">--}}
            {{--<span class="fa fa-envelope"></span>--}}
            {{--Email--}}
            {{--</p>--}}
            {{--<p>--}}
            {{--free@infoexample.com <br>--}}
            {{--www.infoexample.com--}}
            {{--</p>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="footer-bottom">
        <br><br>
        <div class="container">
            <div class="row d-flex">
                <p class="col-lg-12 footer-text text-center">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy; <script>document.write(new Date().getFullYear());</script><br>
                    تمام حقوق متعلق به حساب مهربانی می‌باشد. ساخته شده با
                    <i class="fa fa-heart-o" aria-hidden="true"></i> توسط
                    <a href="https://ultimate-developers.ir" target="_blank">نهایت توسعه</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<!--================ End footer Area  =================-->


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="theme/js/jquery-3.2.1.min.js"></script>
<script src="theme/js/popper.js"></script>
<script src="theme/js/bootstrap.min.js"></script>
<script src="theme/js/stellar.js"></script>
<script src="theme/vendors/lightbox/simpleLightbox.min.js"></script>
<script src="theme/vendors/nice-select/js/jquery.nice-select.min.js"></script>
<script src="theme/js/jquery.ajaxchimp.min.js"></script>
<script src="theme/js/mail-script.js"></script>
<script src="theme/js/countdown.js"></script>
<!--gmaps Js-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
<script src="theme/js/gmaps.min.js"></script>
<script src="theme/js/theme.js"></script>
<script>
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
    })
</script>
</body>
</html>