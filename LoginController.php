<?php
namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class LoginController extends Controller
{
    public function __construct(){
        $this->middleware('revalidate')->except('logout');
        //$this->middleware('guest:company_user')->except('logout');
    }

    public function login(){
        $value = Session::get('user_type');
        if ($value == "company"){
            return redirect()->intended('company/company_dashboard');
        }
        elseif ($value == "customer") {
            return redirect()->intended('customer/customer_profile');
        }else{
            return view('login');
        }
    }

    public function login_to_enquiry(Request $request){
        return view('login')->with('company_id', $request->id)->with('company', $request->company)->with('device', $request->device);
    }

    public function login_proceed(Request $request){
        $this->validate($request, [
            'mobile' => 'required',
            'password' => 'required|min:4'
        ]);
        
        if ($request->selectUserType == "customer"){
            if (Auth::guard('customer')->attempt(['mobile' => $request->input('mobile'), 'password' => $request->input('password')], $request->get('remember'))) {
                $request->session()->regenerate();
                $customer = DB::table('customers')->where('mobile', $request->input('mobile'))->first();
                
                $request->session()->put('customer_id', $customer->id);
                $request->session()->put('email', $customer->email);
                $request->session()->put('fullname', $customer->fullname);
                $request->session()->put('mobile', $request->mobile);
                $request->session()->put('address', $customer->address);
                $request->session()->put('user_type', "customer");

                if ($request->company_id != ""){
                    return redirect()->intended('customer/enquiry/'.$request->company_id.'/'.$request->company.'/'.$request->device);
                }
                else{return redirect()->intended('login');}
                
            }
            else{
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }

        }
        elseif ($request->selectUserType == "company") {
            if (Auth::guard('company_user')->attempt(['mobile' => $request->input('mobile'), 'password' =>    $request->input('password')], $request->get('remember'))) {
                $request->session()->regenerate();
               
                $cmp_user = DB::table('company_users')->where('mobile', $request->input('mobile'))->first();
                $company = DB::table('companies')->where('id', $cmp_user->company_id)->pluck('company');
    
                $request->session()->put('email', $cmp_user->email);
                $request->session()->put('mobile', $request->input('mobile'));
                $request->session()->put('company', $company[0]);
                $request->session()->put('company_id', $cmp_user->company_id);
                $request->session()->put('fullname', $cmp_user->fullname);
                $request->session()->put('user_type', "company");
                $request->session()->put('role', $cmp_user->role);
                $request->session()->put('company_user_id', $cmp_user->id);
                return redirect()->intended('login');
            }
            else{
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }
        }

    }

    public function logout(Request $request){
        $request->session()->flush();
        return redirect()->intended('company/company_dashboard');
    }

}
