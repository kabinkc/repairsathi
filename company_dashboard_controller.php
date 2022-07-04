<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceListModel;
use App\Models\repair_ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\company_user_register;
use Intervention\Image\Facades\Image;

class company_dashboard_controller extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
    }

    public function company_dashboard(){
        if (Session::get('user_type') == "company"){
            $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
            return view('company.company_dashboard', compact('user'));
          }
        else{
            return redirect()->intended("/login");
        }
    }

    public function collected_devices(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('status', 'Collected')->where('company_id', Session::get('company_id'))->orderBy('id', 'desc')->Paginate(14);

        //$devices = DB::table('device_list')->where('status', 'Collected')->paginate(5);
        return view('company.collected_devices', compact('devices'), compact('user'));
    }

    public function enquries(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('status', 'Enquiry')->where('company_id', Session::get('company_id'))->get();
        return view('company.enquries', compact('devices'), compact('user'));
    }

    public function collect_device(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        return view('company.collect_device', compact('user'));
    }

    public function troubleshoot_devices(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('status', 'On-Troubleshoot')->where('company_id', Session::get('company_id'))->get();
        return view('company.on-troubleshoot', compact('devices'), compact('user'));
    }

    public function repair_devices(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('status', 'On-Repair')->where('company_id', Session::get('company_id'))->get();
        return view('company.on-repair', compact('devices'), compact('user'));
    }

    public function returned_devices(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('status', 'Returned')->where('company_id', Session::get('company_id'))->get();
        return view('company.returned', compact('devices'), compact('user'));
    }

    public function all_devices(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        $devices = DB::table('device_list')->where('company_id', Session::get('company_id'))->orderBy('id', 'desc')->Paginate(14);;
        return view('company.all_device', compact('devices'), compact('user'));
    }

    public function all_users(){
        if(Session::get('role') == "Admin"){
            $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
            $users = DB::table('company_users')->where('company_id', Session::get('company_id'))->get();
            return view('company.all_users', compact('users'), compact('user'));
        }
        else{
            return view('company.no_access');
        }
    }

    public function new_staff(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        return view('company.add_new_staff', compact('user'));
    }

    public function current_logged_user(){
        $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
        //dd($user->fullname);
        return view('company.logged_user', compact('user'));
    }

    public function company_setting(){
        if(Session::get('role') == "Admin"){
            $user = DB::table('company_users')->where('id', Session::get('company_user_id'))->first();
            $company = DB::table('companies')->where('id', Session::get('company_id'))->first();
            return view('company.company_setting', compact('company'), compact('user'));
        }
        else{
            return view('company.no_access');
        }
    }

    public function generatePDF()
    {
        $devices = DB::table('device_list')->where('status', 'Collected')->orderBy('id', 'desc')->Paginate(14);
        $pdf = PDF::loadView('company.collected_devices', compact('devices'));
        return $pdf->download('collected_devices.pdf');
    }
    
    public function save_profile_img(Request $request){
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $string = "abcdefghijklmnopqrstuvwxyz0123456789"; 
        $imageName = str_shuffle($string).".".$request->profile_image->extension();
        //dd($imageName);
        $mobile = $request->session()->get('mobile');
        $destinationPath = 'users/'.$mobile;


        $img = Image::make($request->profile_image->getRealPath());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        //$request->profile_image->move($destinationPath, $imageName);
        
        $user_table = company_user_register::find($request->user_id);
        $user_table->profile = $imageName;
        $user_table->save();
        
        return redirect()->back()->with("message", "Profile Image Changed Successfully");
    }

    public function save_device(Request $request){
        $request->validate([
            'collected_date' => 'required',
            'device_name' => 'required|string',
            'enquiry_message' => 'required|string',
            'fullname' => 'required|string',
        ]);
        $ticket_no = "";
        // Transaction
        DB::transaction(function () use($request, &$ticket_no){
            $mobile = $request->input('mobile');
            $ticket = DB::table('repair_tickets')->where('id', 1)->pluck('ticket_no');
            $ticket_no = $ticket[0];
            $newTicket_no = intval($ticket_no) + 1;
            
            //$repair_tickets = new repair_ticket();
            $repair_tickets = repair_ticket::find(1);
            $repair_tickets->ticket_no = $newTicket_no;
            $repair_tickets->save();

            $customer_id = DB::table('customers')->where('mobile', $mobile)->pluck('id');
            $company_id = session('company_id');
    
            if(count($customer_id) > 0){
                $customer_id = $customer_id[0];
            }
            else{
                $customer_id = "";
            }

            $device_list = new DeviceListModel();
            $device_list->device_name = $request->device_name;
            $device_list->attribute = $request->attribute;
            $device_list->enquiry_message = $request->enquiry_message;
            $device_list->enquiry_date = "";
            $device_list->collected_date = $request->collected_date;
            $device_list->repair_entry_date = "";
            $device_list->return_date = $request->return_date;
            $device_list->status = "Collected";
            $device_list->ticket_no = $ticket_no;
            $device_list->company_id = $company_id;
            $device_list->customer_name = $request->fullname;
            $device_list->address = $request->address;
            $device_list->mobile = $request->mobile;
            $device_list->email = "";
            $device_list->customer_id = $customer_id;
            $device_list->save();
        });
        return redirect()->intended('company/collect_device')->with('message', $request->device_name.":".$ticket_no);
    }

    public function change_status(Request $request){
        $status = $request->status;
        $id = $request->id;
        $date = $request->date;
        $ticket_no = $request->ticket_no;

        if($status == "Enquiry" or $status == "On-Troubleshoot"){
            DB::update('update device_list set status = ? where id = ?',[$status, $id]);
            return redirect()->back()->with("message", "Changed Successfully");
        }
        elseif($status == "Collected"){
            DB::update('update device_list set collected_date = ?, status = ? where id = ?',[$date, $status, $id]);
            return redirect()->back()->with("message", "Changed Successfully");
        }
        elseif($status == "On-Repair"){
            DB::update('update device_list set repair_entry_date = ?, status = ? where id = ?',[$date, $status, $id]);
            return redirect()->back()->with("message", "Changed Successfully");
        }
        elseif($status == "Returned"){
            DB::update('update device_list set return_date = ?, status = ? where id = ?',[$date, $status, $id]);
            return redirect()->back()->with("message", "Changed Successfully");
        }
    }

    public function modify_user_details(Request $request){
        DB::beginTransaction();
        try {
            DB::update('update company_users set fullname = ?, address = ?, country = ?, city = ?, mobile = ?, role = ? where id = ?',[$request->fullname, $request->address, $request->country, $request->city, $request->mobile, $request->role, $request->user_id]);
            DB::commit();
            // all good
            if($request->user_id == Session::get('company_user_id')){
                $request->session()->put('fullname', $request->fullname);
                $request->session()->put('role', $request->role);
            }
            return response()->json(['success'=>'Changed Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['success'=>$e->getMessage()]);
        }
        
    }

    public function modify_user_credential(Request $request){
        if($request->credential_type == "email"){
            if ($request->email == ""){
                return response()->json(['success'=>'Error, Email cannot be empty']);
            }
            $found = DB::table('company_users')->where('email', $request->email)->pluck('email');
            $found1 = DB::table('customers')->where('email', $request->email)->pluck('email');
            if(count($found) > 0){
                return response()->json(['success'=>'Email '.$request->email.' has already been taken']);
            }
            elseif(count($found1) > 0){
                return response()->json(['success'=>'Email '.$request->email.' has already been taken']);
            }
        }
        elseif($request->credential_type == "password"){
            if($request->new_password == ""){
                return response()->json(['success'=>'Error, New Password cannot be empty']);
            }
            elseif($request->new_password != $request->repeat_new_password){
                return response()->json(['success'=>'Error, New Password & Repeat Password must be same!']);
            }
        }
        
        if (! Auth::guard('company_user')->attempt(['id' => $request->user_id1, 'password' => $request->input('cur_password')])) {
            return response()->json(['success'=>'Error, Currently Logged User Password Not Matched!!!']);
        }

        DB::beginTransaction();
        try {
            if($request->credential_type == "email"){
                DB::update('update company_users set email = ? where id = ?',[$request->email, $request->user_id1]);
            }
            elseif($request->credential_type == "password"){
                $hashed_new_password = Hash::make($request->new_password);
                DB::update('update company_users set password = ? where id = ?',[$hashed_new_password, $request->user_id1]);

            }
            DB::commit();
            // all good

            if($request->user_id1 == Session::get('company_user_id')){
                if($request->credential_type == "email"){
                    $request->session()->put('email', $request->email);
                }
            }
            return response()->json(['success'=>'Saved Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['success'=>$e->getMessage()]);
        }
    }

    public function modify_company_details(Request $request){
        if($request->credential_type == "email"){
            if ($request->email == ""){
                return response()->json(['success'=>'Error, Email cannot be empty']);
            }
            $found = DB::table('companies')->where('company_email', $request->email)->pluck('company_email');
            if(count($found) > 0){
                return response()->json(['success'=>'Email '.$request->email.' has already been taken']);
            }
        }
        elseif($request->credential_type == "company"){
            if($request->company == ""){
                return response()->json(['success'=>'Error, Company Name cannot be empty']);
            }

            $found = DB::table('companies')->where('company', $request->company)->pluck('company');
            if(count($found) > 0){
                return response()->json(['success'=>'Company Name '.$request->company.' has already been taken']);
            }
        }
        elseif($request->credential_type == "address"){
            if($request->address == ""){
                return response()->json(['success'=>'Error, Address cannot be empty!']);
            }
            elseif($request->country == ""){
                return response()->json(['success'=>'Error, Country cannot be empty!']);
            }
        }
        elseif ($request->credential_type == "phone"){
            if($request->mobile == ""){
                return response()->json(['success'=>'Error, Mobile Number cannot be empty']);
            }
        }
        elseif ($request->credential_type == "messenger-link"){
            if($request->messenger_link == ""){
                return response()->json(['success'=>'Error, Messenger Link cannot be empty']);
            }
        }
        
        if (! Auth::guard('company_user')->attempt(['id' => $request->user_id1, 'password' => $request->input('cur_password')])) {
            return response()->json(['success'=>'Error, Currently Logged User Password Not Matched!!!']);
        }

        DB::beginTransaction();
        try {
            if($request->credential_type == "email"){
                DB::update('update companies set company_email = ? where id = ?',[$request->email, Session::get('company_id')]);
            }
            elseif($request->credential_type == "company"){
                DB::update('update companies set company = ? where id = ?',[$request->company, Session::get('company_id')]);

            }
            elseif($request->credential_type == "address"){
                DB::update('update companies set street_address = ?, country = ?, city = ? where id = ?',[$request->address, $request->country, $request->city, Session::get('company_id')]);

            }
            elseif($request->credential_type == "phone"){
                DB::update('update companies set telephone = ?, company_mobile = ? where id = ?',[$request->telephone, $request->mobile, Session::get('company_id')]);
            }
            elseif ($request->credential_type == "messenger-link"){
                DB::update('update companies set messenger_link = ? where id = ?',[$request->messenger_link, Session::get('company_id')]);
            }

            DB::commit();
            // all good
            if($request->credential_type == "email"){
                $request->session()->put('email', $request->email);
            }
            elseif($request->credential_type == "company"){
                $request->session()->put('company', $request->company);
            }
            return response()->json(['success'=>'Saved Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['success'=>$e->getMessage()]);
        }
    }

    public function save_staff(Request $request){
        $request->validate([
            'fullname' => 'required|string',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required|unique:company_users',
            'mobile' => 'unique:customers',
            'email' => 'required|email|unique:company_users',
            'email' => 'unique:customers',
            'password' => 'required|min:4|max:30',
        ]);

        $company_user = new company_user_register;
        $company_user->fullname = $request->fullname;
        $company_user->address = $request->street_address;
        $company_user->country = $request->country;
        $company_user->city = $request->city;
        $company_user->email = $request->email;
        $company_user->mobile = $request->mobile;
        $password = Hash::make($request->input('password'));
        $company_user->password = $password;
        $company_user->company_id = Session::get('company_id');
        $company_user->role = $request->role;
        $company_user->save();

        return redirect()->intended('company/add_staff')->with('message', 'Staff User Added Successfully');
    }
}