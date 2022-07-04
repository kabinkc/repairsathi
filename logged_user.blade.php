@extends('layouts.company.main')
@section('main-section')
<div class="container-fluid" style="padding:0px;display:flex;">
    <div class="main" style="height:100%;position:sticky;top:50px;">
        <aside>
          <div class="sidebar left ">
            <div class="user-panel" style="background:black;">
              <div class="pull-left image">
                
                <img src="{{ url('users/'.$user->mobile.'/'.$user->profile) }}" class="rounded-circle" alt="User Image">
              </div>
              <div class="pull-left info">
			  <a href="{{ url('company/current_logged_user') }}" style="text-decoration:none;display:block;padding:5px;">{{ session('fullname') }}<br><br>
               <i class="fa fa-circle text-success"></i>Online</a>
              </div>
            </div>
            <ul class="list-sidebar bg-defoult">
            <li> <a href="{{url('company/company_dashboard')}}"><span class="nav-label">DASHBOARD</span></a> </li>
            <li> <a href="{{ url('company/enquries') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Enquries</span></a> </li>
			<li><a href="{{ url('company/collect_device') }}" style=''>
            <i class="fas fa-plus" style="font-size:10px;margin-left:5px;"></i><br>
	        <i class="fas fa-box-open" style="margin-right:10px;font-size:17px;"></i>
            <span class="nav-label">Collect Device</span></a></li>
            <li> <a href="{{ url('company/collected_devices') }}"><i class="fa fa-laptop"></i> <span class="nav-label">Collected Devices</span></a> </li>
			<li> <a href="{{ url('company/on-troubleshoot') }}"><i class="fa fa-laptop"></i> <span class="nav-label">On - Troubleshoot</span></a> </li>
            <li> <a href="{{ url('company/on-repair') }}"><i class="fa fa-laptop"></i> <span class="nav-label">On - Repair Devices</span></a> </li>
            <li> <a href="{{ url('company/returned_devices') }}"><i class="fa fa-laptop"></i> <span class="nav-label">Returned Devices</a></li>
          <li> <a href="{{ url('company/all_devices') }}"><i class="fa fa-laptop"></i> <span class="nav-label">All Devices</span></a> </li>
          <li><a href="#" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#tables"><i class="fa fa-table"></i> <span class="nav-label">Staff</span><span class="fa fa-chevron-left pull-right"></span></a>
          <ul  class="sub-menu collapse" id="tables">
		  <li><a href="{{ url('company/all_users') }}">All Staff</a></li>
            <li><a href="{{ url('company/add_staff') }}">Add Staff</a></li>
          </ul>
        </li>
        <li> <a href="#"><i class="fa fa-shopping-cart"></i><span class="nav-label">Customer List</span></a>
      </li>

    </ul>
    </div>
    </aside>
    </div>

<div>
<label for="profile_image" style="margin-top:15px;"><b>&nbsp;&nbsp;Profile upload</b></label>
<form method='post' action="{{ url('company/save_profile_img') }}" enctype="multipart/form-data">
<input type="text" id="user_id" name="user_id" readonly value="{{ $user->id }}" style="margin-bottom:15px;border:none;"><br>    
    
@csrf
<input type="file" class='textType1' name="profile_image" id="profile_image" accept="image/jpg, image/png, image/jpeg" value="{{ old('profile_image') }}" style="width:95%;">
@error('profile_image')
<div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
@enderror
@if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
@endif
<button type='submit' id='save-security-btn' style="background:#333333;padding:10px;border:none;border-radius:3px;color:white;width:180px;margin-top:10px;margin-left:10px;">SAVE</button>
</form>
<br><br>
<hr>

<label><b>&nbsp;&nbsp;Full Name:</b></label>
<input type="text" id="fullname" name="fullname" class="textType1" style="width:95%;" value="{{ $user->fullname }}">
<br><br>

<label><b>&nbsp;&nbsp;Address:</b></label>
<input type="text" id="address" name="address" class="textType1" style="width:95%;" value="{{ $user->address }}"><br><br>

<label style="width:50%;"><b>&nbsp;&nbsp;Country:</b></label><label style="width:50%;"><b>&nbsp;&nbsp;City:</b></label>
<div style="display:flex;">
<input type="text" id="country" name="country" class="textType1" style="width:50%;" value="{{ $user->country }}">
<input type="text" id="city" name="city" class="textType1" style="width:50%;" value="{{ $user->city }}">
</div>

<label><b>&nbsp;&nbsp;Mobile:</b></label>
<input type="text" id="mobile" name="mobile" class="textType1" style="width:95%;" value="{{ $user->mobile }}"><br><br>

<div style="margin-left:15px;">
<label style="margin-bottom:10px;"><b>User Role:</b></label><br>
@if(Session('role') == "Admin")
<input type="radio" name='status' value="Admin" id="Admin" checked>&nbsp;&nbsp;&nbsp;Admin<br>
<input type="radio" name='status' value="Staff" id="Staff">&nbsp;&nbsp;&nbsp;Staff
@endif

@if(Session('role') == "Staff")
<input type="radio" name='status' value="Staff" id="Staff" checked>&nbsp;&nbsp;&nbsp;Staff
@endif
</div>

<br><br>
<button type='button' id='save-modify-btn' style="background:#333333;padding:8px;border:none;color:white;width:150px;margin-top:10px;margin-left:10px;font-size:0.9em;" onclick="save_user_modified()">Save</button>
</div>

<div id="security-div1">
	<input type="text" id="user_id1" name="user_id1" value={{ $user->id }} readonly style="margin-bottom:15px;border:none;">
<br>
	<label><b>&nbsp;&nbsp;Email:</b></label>
	<a href="#" class="" style="color:blue;padding:10px;height:40px;" onclick="edit_email()">
	<i class="fas fa-edit"></i>Edit Email</a>
	<input type="email" id="email" name="email" class="textType1" style="color:gray;background:white;border:1px solid #E6DFDF;" readonly value="{{ $user->email }}">
	<br>
	<button type='button' style="background:#333333;padding:8px;border:none;color:white;width:150px;margin-top:10px;margin-left:10px;font-size:0.9em;" onclick="confirm_pass('email')">Save</button>
	<br><br>
	<hr style="margin:15px 0px 0px 10px;">

	<label for="psw" style="margin-top:15px;"><b>&nbsp;&nbsp;New Password</b></label><br>
	<input type="password" class='textType1' placeholder="Enter Password" name="new_password" id="new_password">

	<label for="psw-repeat" style="margin-top:15px;"><b>&nbsp;&nbsp;Repeat New Password</b></label><br>
	<input type="password" class='textType1' placeholder="Repeat Password" name="repeat_new_password" id="repeat_new_password">
	<br>
	<button type='button' style="background:#333333;padding:8px;border:none;color:white;width:150px;margin-top:10px;margin-left:10px;font-size:0.9em;" onclick="confirm_pass('password')">Save</button>

	<div id="current-pass-div">
	<input type="hidden" id="credential_type" readonly>
	<input type="button" style="background:#DC3545;padding:10px;border:none;color:white;width:150px;float:right;" onclick="cancel_confirm_pass1()" value="Cancel"><br><br>

	<label for="cur_password" style="margin-top:15px;"><b>Provide Current Password</b></label><br>
	<input type="password" class='textType1' placeholder="Enter Current Password" name="cur_password" id="cur_password" style="background:white;">
	@error('cur_password')
	<div class="alert alert-danger" style="border:none;border-radius:0px;padding:5px;margin:0px;">{{ $message }}</div>
	@enderror

	<button type='button' id='save-security-btn' style="background:#333333;padding:10px;border:none;border-radius:3px;color:white;width:180px;margin-top:10px;margin-left:10px;" onclick="save_credential()">Confirm</button>
	</div>
</div>

</div>

<div id="blackDiv"></div>
@endsection