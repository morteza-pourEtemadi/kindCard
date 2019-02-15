@extends('layouts.master')
@section('title', 'خانه')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection

@section('content')
    <!--================ Home Banner Area =================-->
    <section class="home_banner_area">
        <div class="banner_inner">
            <div class="container">
                <div class="banner_content">
                    <p class="upper_text">دست به دست هم دهیم به مهر</p>
                    <h3 class="text-white">ميهن خويش را کنيم آباد</h3>
                    <pre class="text-white f-500">يار و غم‌خوار همدگر باشيم        تا بـمـانـيـم خرم و آزاد</pre>
                    <br>
                    <a class="primary_btn mr-20" onclick="$('#depositModal').modal();">اکنون کمک کنید</a>
                    <a class="primary_btn yellow_btn text-white" href="#causes">نیاز به دلیل دارید؟</a>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

    <!--================ Start About Area =================-->
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
            <div class="text-center pb-3" dir="rtl">
                <h3>به همین راحتی</h3>
                <p>
                    ایده‌ی اولیه‌ی این حساب، از دیوارهای مهربانی گرفته شده است.
                </p>
            </div>
        </div>
    </section>
    <!--================ End About Area =================-->

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
                                            <a onclick="$('#depositModal').modal();" class="primary_btn">کمک کنید</a>
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
    <!--================ Start Terms Area =================-->
    <section class="event_area section_gap_custom mt-5 pt-5 pb-5 px-0">
        <div class="container mt-5 pt-5">
            <div class="main_title mb-5 pb-5">
                <h2>قوانین و مقررات این حساب</h2>
                {{--<p></p>--}}
            </div>

            <div class="row justify-content-center pb-5">
                <div class="col-lg-8">
                    <div class="single_event">
                        <div class="content_wrapper text-right" dir="rtl">
                            <ol class="terms">
                                <li>در راستای سرعت بخشیدن به عملیات کمک‌رسانی، ما بنا را بر اصل صداقت و راستگویی گذاشته
                                    و
                                    خواهشمندیم که در صورت وجود نیاز واقعی، از منابع این حساب استفاده نمایید.
                                </li>
                                <li>حساب مهربانی حق خود می‌داند که برای برداشت مبالغ بالای 1 میلیون تومان، بررسی‌های
                                    لازم را
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
    <!--================ End Terms Area =================-->

    <!--================ Start CTA Area =================-->
    <div class="cta-area section_gap overlay">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" dir="rtl">
                    <h1>شما هم حامی هستید؟</h1>
                    <p>
                        می‌گویند که بال زدن یک پروانه در صحرای آفریقا می‌تواند طوفانی در آمریکا به پا کند.<br>
                        به یک تغییر بیندیشید و عزم خود را جمع کنید. شاید کمک‌های به ظاهر کوچک هم بتواند تغییری بزرگ
                        ایجاد
                        کند.
                    </p>
                    <a onclick="$('#depositModal').modal();" class="primary_btn yellow_btn rounded">به جمع حامیان
                        بپیوندید</a>
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
            <div class="row text-center">
                <div class="col-lg-5 col-md-4 col-sm-12">
                    <h1 class="text-white">آیا مایلید از ما بیشتر بدانید؟</h1>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <form class="subscribe_form relative">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-10 col-md-8 col-sm-12">
                                <input name="EMAIL" type="email" placeholder="آدرس ایمیل شما" required="" id="sub-email"
                                       onfocus="this.placeholder = ''" onblur="this.placeholder = 'آدرس ایمیل'">
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <a class="primary_btn yellow_btn btn sub-btn rounded">اشتراک در خبرنامه</a>
                            </div>
                        </div>
                        <div class="info"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--================ End Subscribe Area =================-->

    <!--================ Start Modal Area =================-->
    <div class="modal" id="depositModal" role="dialog" aria-labelledby="myModalLabel" dir="rtl">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">اکنون کمک کنید</h4>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">بستن</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <label for="price">لطفا مبلغ مورد نظر را به تومان وارد نمایید.</label>
                            <div class="col-lg-12 m-0 px-0 pt-1 pb-3" id="badges">
                                <span class="numBadge badge badge-success p-2">{{number_format(5000)}}</span>
                                <span class="numBadge badge badge-success p-2">{{number_format(10000)}}</span>
                                <span class="numBadge badge badge-success p-2">{{number_format(50000)}}</span>
                                <span class="numBadge badge badge-success p-2">{{number_format(100000)}}</span>
                                <span class="numBadge badge badge-success p-2">{{number_format(500000)}}</span>
                            </div>
                            <div class="row">
                                <div class="col-lg-9">
                                    <input type="text" name="price" id="price" class="input-group rounded ml-0 pl-0"
                                           placeholder="مبلغ دلخواه">
                                </div>
                                <div class="col-lg-3 mr-0 pr-0 text-right">
                                    تومان
                                </div>
                            </div>
                            <div class="row text-center pt-3">
                                <img class="loading" src="{{'theme/img/loading.gif'}}"/>
                                <div class="col-lg-12">
                                    <button id="sendAid" class="btn btn-outline-primary" href="#">ارسال کمک</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--================ End Modal Area =================-->
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('jquery/jquery.number.min.js')}}"></script>
    <script>
        $(function () {
            // Set up the number formatting.
            $('#number_container').slideDown('fast');

            $('#price').on('change', function () {
                var val = $('#price').val();
                $('#the_number').text(val !== '' ? val : '(empty)');
            }).number(true, 0);
        });

        function sendAid() {
            var txt = $('#price').val();
            var len = txt.length;

            var price = '';
            for (i = 0; i < len; i++) {
                var str = txt.charAt(i);
                if (str === ',') {
                    continue;
                }
                price += (isNaN(parseInt(str)) ? parseInt(txt.charCodeAt(i)) : parseInt(str));
            }

            if (isNaN(price) || price === '') {
                $.notify({
                    message: 'لطفا یک مقدار عددی صحیح وارد نمایید'
                }, {
                    z_index: 1000000,
                    type: 'danger'
                });
                return;
            }

            startLoading($('#sendAid'));
            $.ajax({
                method: 'POST',
                url: '{{route('send.aid')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    price: price
                },
            }).done(function (response) {
                stopLoading($('#sendAid'));
                if (response.type === 'success') {
                    window.location.href = response.url;
                }
                $.notify({
                    message: response.message
                }, {
                    z_index: 1000000,
                    type: response.type
                });
            });
        }

        $('document').ready(function () {
            $('.numBadge').click(function () {
                $('#price').val($(this).text());
            });

            $('#sendAid').click(function (e) {
                e.preventDefault();
                sendAid();
            });
            $('#price').on('keypress', function (e) {
                if (e.which === 13) {
                    sendAid();
                }
            });
        })
    </script>
@endsection