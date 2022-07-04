@extends('layouts.main')
@section('main-section')
<div class="container">
    <div class='row'>
        <div class='col-md-12 col-12'>
        @if(Request::path() == "register_customer")
        <h1 style="font-weight:100;">{{__('Registering')}}<span style="font-size:18px;font-weight:bold;">&nbsp;{{__('As a new customer')}}</span></h1>
        @else
        <h1 style="font-weight:100;">{{__('Register')}}<span style="font-size:18px;font-weight:bold;">&nbsp;{{__('For enquiry')}}</span></h1>
        @endif
            <hr>
        </div>
    </div>
    <form action="{{ url('save_customer') }}" method="POST">
    @csrf 
    <div class='row' id='register_form'>
        <div class="col-md-4 col-12"> 
            <label for="fullname"><b>{{__('Full Name')}}</b></label><br>
            <input type="text" class='textType' placeholder="Full Name" name="fullname" id="fullname" value="{{ old('fullname') }}">
            @error('fullname')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror
            <!--
            <label for="address" style="margin-top:15px;"><b>Street Address</b></label><br>
            <input type="text" class='textType' placeholder="Street Address" name="address" id="address" value="{{ old('address') }}">
            @error('address')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <div style="display:flex;margin-top:15px;flex-wrap:wrap;">
            <label for="country" style="width:50%;"><b>Country</b></label>
            <label for="city" style="width:50%;"><b>City</b></label>

            <select type="text" class='textType' placeholder="Country" name="country" id="country" style="width:50%;border-right:7px solid #FFFFFF;">
            <option>Nepal</option>
            <option>India</option>
            <option>United States</option>
            <option>Indonesia</option>
            <option>Pakistan</option>
            </select>

            <input type="text" class='textType' placeholder="City" name="city" id="city" value="{{ old('city') }}" style="width:50%;">
            @error('city')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror
            </div>
            
            <label for="email" style="margin-top:15px;"><b>Email</b></label><br>
            <input type="text" class='textType' placeholder="Email" name="email" id="email" value="{{old('email')}}">
            @error('email')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror
            -->
            
            <label for="mobile" style="margin-top:15px;"><b>{{__('Mobile')}}</b></label>
            <input type="text" class='textType' placeholder="Mobile" name="mobile" id="mobile" value="{{old('mobile')}}">
            @error('mobile')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <label for="psw" style="margin-top:15px;"><b>{{__('Password')}}</b></label><br>
            <input type="password" class='textType' placeholder="Enter Password" name="password" id="psw">
            @error('password')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <label for="psw-repeat" style="margin-top:15px;"><b>{{__('Repeat Password')}}</b></label><br>
            <input type="password" class='textType' placeholder="Repeat Password" name="password_confirmation" id="psw-repeat">
            @error('password_confirmation')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <p style='font-size:0.8em;margin-top:15px;'>{{__('By Creating an Account')}}&nbsp;<a href="#">Terms & Privacy</a>.</p>
            <button type="submit" class="registerbtn btn-primary" style='width:200px;padding:10px;'>{{__('Register')}}</button>
        @if(Request::path() == "register_customer")
        
        @else
        <input type="text" name="company_id" id="company_id" value="{{ $company_id }}" style="width:50%;height:10px;float:left;">
        <input type="text" name="company" id="company" value="{{ $company }}" style="width:50%;height:10px;">
        <input type="text" name="device" id="device" value="{{ $device }}">
        @endif
        </div>

    </div>
    </form>
  
</div>
@endsection