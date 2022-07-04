<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\companie;
use App\Models\company_user_register;
use App\Models\customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class register_controller extends Controller
{
    public function register_option(){
        return view('register_option');
    }

    public function register_business(){
        return view('register_business');
    }

    public function register_customer(){
        return view('register_customer');
    }

    public function register_customer1(Request $request){
        return view('register_customer', ['company_id' => $request->id, 'company' => $request->company, 'device' => $request->device]);
    }

    public function save_company(Request $request)
    {
        $request->validate([
            'company' => 'required|string|unique:companies',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'company_mobile' => 'required|unique:companies',
            'fullname' => 'required',
            'email' => 'required|email|unique:company_users',
            'email' => 'unique:customers',
            'mobile' => 'required|unique:company_users',
            'mobile' => 'unique:customers',
            'password' => 'required|min:4|max:25|confirmed',
        ]);
        
        $company = new companie; 
        $company->company = $request->input('company');
        $company->code_name = "";
        $company->street_address = $request->input('street_address');
        $company->country = $request->input('country');
        $company->city = $request->input('city');
        $company->company_email = $request->input('company_email');
        $company->telephone = $request->input('telephone');
        $company->company_mobile = $request->input('company_mobile');
        $company->save();

        $company_id = $company->id;

        $company_user = new company_user_register;
        $company_user->fullname = $request->input('fullname');
        $company_user->address = "";
        $company_user->country = $request->input('country');
        $company_user->city = "";
        $company_user->email = $request->input('email');
        $company_user->mobile = $request->input('mobile');
        $password = Hash::make($request->input('password'));
        $company_user->password = $password;
        $company_user->company_id = $company_id;
        $company_user->role = $request->input('role');
        $company_user->save();
        
        $path = "users/".strval($request->input('mobile'));
        //dump($path);
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        } 
        
        //$request->session()->put('user-type', $request->input('company'));
        //$request->session()->put('mobile', $request->input('mobile'));
        //$value = $request->session()->get('key', 'default');
        return redirect()->intended('login')->with('message', 'Company Registered Successfully');
        /*DB::beginTransaction();

        try{
           

            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
        }*/
       
    }  
    public function save_customer(Request $request){
        $request->validate([
            'fullname' => 'required|string',
            'mobile' => 'required|unique:customers|unique:company_users',
            'password' => 'required|min:4|max:25|confirmed',
        ]);
        $customers = new customer();
        $customers->fullname = $request->fullname;
        $customers->mobile = $request->mobile;
        //$customers->email = $request->email;
        $password = Hash::make($request->input('password'));
        $customers->password = $password;
        $customers->save();

        $customer_id = $customers->id;

        if ($request->company_id != ""){
            $request->session()->put('customer_id', $customer_id );
                //$request->session()->put('email', $request->email);
                $request->session()->put('fullname', $request->fullname);
                $request->session()->put('mobile', $request->mobile);
                //$request->session()->put('address', $request->address);
                $request->session()->put('user_type', "customer");
            return redirect()->intended('customer/enquiry/'.$request->company_id.'/'.$request->company.'/'.$request->device);
           
        }
        else{
            return redirect()->intended('login')->with('message', 'Customer Account Created Successfully');
        }
        
    }

}