@extends('layouts.master')
@section('title', 'تماس با ما')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/contact-us.css')}}">
@endsection

@section('content')
    <section class="contact_area py-5" id="contact">
        <div class="container py-5">
            <div class="main_title pt-5">
                <h2>تماس با ما</h2>
            </div>
            <div class="row team_inner pb-5" dir="rtl">
                <div class="col-xl-6 col-ls-6 col-sm-6 px-5 py-5">
                    <div class="row bg-navy p-3 m-3 text-right">
                        <div class="col-sm-2 col-xs-2"><i class="fa fa-envelope-o" aria-hidden="true"></i></div>
                        <div class="col-sm-10 col-xs-10">
                            <h3>ایمیل ادمین مجموعه</h3>
                            <a class="contact" id="mail" href="mailto:admin@kindcard.ir" target="_blank">
                                admin@kindcard.ir
                            </a>
                        </div>
                        <div class="col-sm-2 col-xs-2"><i class="fa fa-telegram" aria-hidden="true"></i></div>
                        <div class="col-sm-10 col-xs-10">
                            <h3>ربات تلگرامی حساب مهربانی</h3>
                            <a class="contact" id="telegram" href="https://t.me/kind_card_bot" target="_blank">
                                @kind_card_bot
                            </a>
                        </div>
                        <div class="col-sm-2 col-xs-2"><i class="fa fa-comments-o" aria-hidden="true"></i></div>
                        <div class="col-sm-10 col-xs-10">
                            <h3>شماره پیامک موارد ضروری</h3>
                            <small class="text-warning pt-0 mt-0 mb-3 f-100 d-block">(تماس به ندرت پاسخ داده می‌شود.)</small>
                            <a class="contact" id="text" href="sms:+989336365162" target="_blank" dir="ltr">
                                0933 6365 162
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-ls-6 col-sm-6 px-5 py-5">
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="name" class="control-label"> نام و نام خانوادگی</label>
                        <input class="form-control" id="name" type="text" name="name">
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="userEmail" class="control-label">ایمیل</label>
                        <input class="form-control" id="userEmail" type="text" name="email">
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="message" class="control-label">پیام</label>
                        <textarea id="message" class="form-control" name="message"></textarea>
                    </div>
                    <div class="alert display-msg alert-light" role="alert" style="display: none">
                        پیام شما با موفقیت ارسال شد
                    </div>
                    <div class="container-contact2-form-btn ">
                        <div class="wrap-contact2-form-btn">
                            <div class="contact2-form-bgbtn"></div>
                            <button id="contact-form" class="contact2-form-btn">
                                ارسال
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('theme/js/global.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/js/text-field.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#contact-form').click(function (e) {
                e.preventDefault();
                $('.display-msg').addClass('alert-light').removeClass('alert-success');

                function showMsg(err_msg, isOK) {
                    var cls = isOK === false ? 'fail' : '';
                    $('.display-msg').addClass(cls).html(err_msg).show(300);
                    $('#userEmail').focus();
                    setTimeout(function() {
                        $('.display-msg').hide(300);
                    }, 2000);
                }

                var email = $('#userEmail').val().trim();
                var textArea = $('textarea').val();
                var name = $('#name').val();

                // check name field not empty
                if (name.length < 1) {
                    showMsg('لطفا نام خود را وارد کنید', false);
                    $('#name').focus();
                    return;
                }

                // check email not empty
                if (email.length < 1) {
                    showMsg('لطفا ایمیل را وارد کنید', false);
                    $('#userEmail').val('');
                    return;
                }
                // check email format
                var pattern = /^([\w!.%+\-\*])+@([\w\-])+(?:\.[\w\-]+)+$/;
                if (!pattern.test(email)) {
                    showMsg('فرمت ایمیل صحیح نیست', false);
                    return;
                }
                // check textArea not empty
                if (textArea.length < 1) {
                    showMsg('لطفا پیام خود را بنویسید', false);
                    $('textarea').focus();
                    return;
                }

                var data = {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    name: name,
                    text: textArea,
                };

                $.ajax({
                    accept: 'application/json',
                    url: "{{route('contact')}}",
                    method: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('#userEmail').prop('disabled', true);
                        $('#contact-form').prop('disabled', true);
                    },
                    complete: function () {
                        $('#userEmail').prop('disabled', false);
                        $('#contact-form').prop('disabled', false);
                    }
                }).done(function (apiResponse) {
                    if (apiResponse.status === 'success') {
                        showMsg(apiResponse.message, true);
                        $('.display-msg').removeClass('alert-light').addClass('alert-success');
                        $('#userEmail').val('');
                        $('textarea').val('');
                        $('#name').val('');
                    } else {
                        showMsg(apiResponse.message, false);
                    }
                });
            });
        })
    </script>
@endsection