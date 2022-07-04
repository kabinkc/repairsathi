@extends('layouts.main')
@section('main-section')
<div class='container'>
    <div class='row' id='account-type-row'>
        <div class='col-md-2 col-6 account-type-div'>
            <a href="{{ url('/register_customer') }}" class='account-title'>
            <img src="{{ URL('asset/image/customer.png') }}">    
            {{ __('Customer Account') }}</a> 
        </div>
        <div class='col-md-2 col-6 account-type-div'>
            <a href="{{ url('/register_company') }}" class='account-title'>
            <img src="{{ URL('asset/image/shop.png') }}">    
            {{ __('Business Account') }}</a>
        </div>
    </div>
</div>
@endsection