@extends('layouts.main')
@section('main-section')
<div class="container-fluid" id = "login-div">
    <div class="row">
        <div class='col-12 col-md-4'></div>
        <div class='col-12 col-md-4'>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif

            <div style="padding:20px;color:#52595E;text-align:center;">-- {{__('Login')}} --</div>
            <form action="{{ url('go_for_company_login') }}" method='POST'>
            @csrf
            <input type="radio" name="selectUserType" value="customer" checked>
            {{__('Customer')}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="selectUserType" value="company">
            {{__('Company')}}

            <input type='text' name="mobile" class='textType' id = "mobile" placeholder="{{__('Mobile')}}" value="{{ old('mobile') }}">
            @error('mobile')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <input type='password' name="password" class='textType' placeholder="{{__('Password')}}">
            @error('password')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <input type="submit" value="{{__('Login')}}" name="submit_login" style="background-color:blue;color:white;padding:10px;border:none;width:100%;margin-top:20px;">
            <br><br>
            @if(Request::path() == "login")
            
            @else
            <input type="text" value="{{ $company_id }}" name='company_id'>
            <input type="text" value="{{ $company }}" name='company'>
            <input type="text" value="{{ $device }}" name='device'>
            @endif
            </form>
        </div>
        <div class='col-12 col-md-4'></div>
    </div>
</div>
@endsection

