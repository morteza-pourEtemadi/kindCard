@extends('emails.layouts.master-template')
@section('content')
    <p>شما یک پیغام جدید دارید:</p>
    <p>نام ارسال کننده: {{$name}}</p>
    <p>ایمیل ارسال کننده: {{$email}}</p>
    <p>متن پیام: </p>
    <p>{{$text}}</p>
@endsection