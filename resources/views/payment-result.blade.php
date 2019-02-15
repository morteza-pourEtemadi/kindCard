@extends('layouts.master')
@section('title', 'تایید پرداخت وجه')
@section('styles')
    <style>
        body {
            background-color: #5ce5aa;
            background: linear-gradient(to bottom right, #5ce5aa, #f197a2) no-repeat center center fixed;
            background-size: cover;
        }
        #result {
            min-height: 540px;
        }
    </style>
@endsection
@section('content')
    <div id="result" class="container mt-5 pt-5">
        <div class="row mt-5 pt-5">
            <div class="col-lg-9 col-sm-12 m-auto text-center pt-5 mt-5">
                @if($status == 'success')
                    <p style="font-size: x-large;">
                        با تشکر از شما خیر گرامی، موجودی حساب مهربانی به <b style="color: #ee316b;">{{$balance}}تومان </b>افزایش پیدا کرد.
                    </p>
                    <p class="badge d-block pt-4" style="font-size: large; color: #4360a8;">
                        حالا افراد بیشتری می توانند به آرزوهایشان برسند.
                    </p>
                @else
                    <p style="color:#fff; font-size: x-large;">{{$catchMessage}}</p>
                @endif
                @if(isset($username))
                    <button class="btn btn-primary">بازگشت به ربات</button>
                @else
                    <a class="btn btn-primary mt-4" href="{{route('index')}}">بازگشت</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        @if(isset($username))
        $(document).ready(function () {
            $('.btn').click(function () {
                window.location.href = 'https://t.me/{{$username}}'
            })
        });
        @endif
    </script>
@endsection
