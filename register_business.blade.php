@extends('layouts.main')
@section('main-section')
  <div class="container">
    <div class='row'>
        <div class='col-md-12 col-12'>
        <h1 style="font-weight:100;">{{__('Registering')}}<span style="font-size:18px;font-weight:bold;">&nbsp;{{__('As a new business')}}</span></h1>
            <p>{{__('Already have an account')}}<a href="{{ URL('login') }}">&nbsp;{{__('Sign in')}}</a>.</p>
            <hr>
        </div>
    </div>
    <form action='save_company' method="POST">
    @csrf
    <div class='row' id='register_form'>
        
       
        <div class="col-md-4 col-12">
          <div>
          --{{ __('Company Details') }}--</div>

            <label for="company" style='margin-top:20px;'><b>{{__('Company Name')}}</b></label><br>
            <input type="text" class='textType' placeholder="Company Name" name="company" id="company" value="{{ old('company') }}">
            @error('company')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror


            <label for="street_address" style="margin-top:25px;"><b>{{__('Street Address')}}</b></label><br>
            <input type="text" class='textType' placeholder="Company Street Address" name="street_address" id="street_address" value="{{ old('street_address') }}">
            @error('street_address')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <div style="display:flex;margin-top:25px;flex-wrap:wrap;">
            <label for="country" style="width:50%;"><b>{{__("Country")}}</b></label>
            <label for="city" style="width:50%;"><b>{{__('City')}}</b></label>

            <select type="text" class='textType' placeholder="Country" name="country" id="country" style="width:50%;border-right:7px solid #FFFFFF;">
            <option>{{__('Nepal')}}</option>
            <option>{{__('China Hong Kong')}}</option>
            <option>{{__('United States')}}</option>
            </select>

            <input type="text" class='textType' placeholder="City" name="city" id="city" value="{{ old('city') }}" style="width:50%;">
            @error('city')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror
            </div>

            <label for="company_email" style="margin-top:25px;"><b>{{__('Company Email')}}</b></label><br>
            <input type="text" class='textType' placeholder="Company Email" name="company_email" id="company_email" value="{{old('company_email')}}">
            @error('email_email')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
            @enderror

            <div style="display:flex;margin-top:25px;flex-wrap:wrap;">
            <label for="telephone" style="width:50%;"><b>{{__('Telephone')}}</b></label>
            <label for="company_mobile" style="width:50%;"><b>{{__('Mobile')}}</b></label>
            
            <input type="text" class='textType' placeholder="Telephone" name="telephone" id="telephone" value="{{old('telephone')}}" style="width:50%;border-right:7px solid white;">

            <input type="text" class='textType' placeholder="Mobile" name="company_mobile" id="company_mobile" value="{{old('company_mobile')}}" style='width:50%;'>
            @error('company_mobile')
            <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;width:50%;">{{ $message }}</div>
            @enderror

            </div>
        </div>

        <div class='col-md-4 col-12' style='padding:0px 30px 0px 30px;border-left:1px solid #B4B4BF;'>
          <label for="fullname" style="margin-top:15px;"><b>{{__('Admin Full Name')}}</b></label><br>
          <input type="text" class='textType' placeholder="Full Name" name="fullname" id="fullname" value="{{old('fullname')}}">
          @error('fullname')
          <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
          @enderror

          <label for="email" style="margin-top:15px;"><b>{{__('Admin Email')}}</b></label><br>
          <input type="text" class='textType' placeholder="Admin Email" name="email" id="email" value="{{old('email')}}">
          @error('email')
          <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
          @enderror

          <label for="mobile" style="margin-top:15px;"><b>{{__('Admin Mobile')}}</b></label><br>
          <input type="number" class='textType' placeholder="Admin Mobile" name="mobile" id="mobile" value="{{old('mobile')}}">
          @error('mobile')
          <div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
          @enderror

          <label for'role' style="margin-top:15px;"><b>{{__('Select Role')}}</b></label><br>
          <select name='role' id='role'class='textType'><option>Admin</option></select>
          
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
        </div>
        

    </div>
    <div class="row">
    <div class="col-md-8 col-12" style='text-align:center;'>
    <p style='font-size:0.8em;margin-top:15px;'>{{__('By Creating an Account')}}<a href="#">&nbsp;Terms & Privacy</a>.</p>
            <button type="submit" class="registerbtn btn-primary" style='width:200px;padding:10px;'>{{__('Register')}}</button>
    </div>

    </div>
    </form>


</div>
@endsection