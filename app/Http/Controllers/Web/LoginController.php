<?php

namespace App\Http\Controllers\Web;


use App\From;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Voucher;
use Illuminate\Support\Facades\Session;
use Auth;
use DateTime;



class LoginController extends Controller
{
    public function index(Request $request) {
        
        if (Session::has('user')) {
            //Marketing    //Sale_Reporter     //Delivery_Person     //Stock    //Owner
            $date = new DateTime('Asia/Yangon');

            $current_Date = $date->format('Y-m-d');

            if($request->session()->get('user')->role == "Delivery_Person"){

            return redirect()->route('getArrivedOrders');
            }else {
            return redirect()->route('sale_history');
            }                        
        } 
        else{
            // dd('hello');

            return view('login');
          
        }       
		
	}

    public function loginProcess(Request $request){
    
        $validator = Validator::make($request->all(), [
            'user_code' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->where('user_code', $request->user_code)->first();
        

        if (!isset($user)) {

            alert()->error('Wrong User Code Or Email!');

            return redirect()->back();
        
        }
        elseif (!\Hash::check($request->password, $user->password)) {
            
            alert()->error('Wrong Password!');

            return redirect()->back();
        
        }
        
          $request->session()->put('user',$user);
        
        // if(!Session::isStarted()){
        //     Session::start();
        // }
        
        

       //Session::put('role',"Owner");
    //   session()->push('user',$user);
 //   session(['user' => $user]);
    
     //  session()->save();
        //$request->session()->put('name',$user->name);
        //$request->session()->put('email',$user->email);

        $today_date = (new DateTime)->format('Y-m-d');

        $last_date = date('Y-m-d', strtotime('-1day', strtotime($today_date)));

        $today_sale = 0;

        $last_day_sale = 0;

        $today_vouchers = Voucher::where('order_date', $today_date)->get();

        $last_date_vouchers = Voucher::where('order_date', $last_date)->get();

        foreach ($today_vouchers as $tdy) {
            
            $today_sale += $tdy->total_price;
        }

        foreach ($last_date_vouchers as $last) {
            
            $last_day_sale += $last->total_price;
        }

        $request->session()->put('today_sale', $today_sale);

       $request->session()->put('last_day_sale', $last_day_sale);
        
        
        //Session::save();
    //       var_dump(session()->get('user'));
    //   die();

        // if($user){
        //     view()->share('user',$user);
        //     if($user->role == "Delivery_Person"){
        //          return redirect()->route('getArrivedOrders');
        //     }else{
        //         return redirect()->route('sale_history')->with(['role' => 'Owner']);
        //     }
        // }

       return redirect()->route('index');
        

    }
    

    public function logoutProcess(Request $request){

        Session::flush();
        
        alert()->success("Successfully Logout");

        return redirect()->route('index');

    }

    public function getChangePasswordPage(){

        return view('change_pw');
    }

    protected function updatePassword(Request $request){

        $validator = Validator::make($request->all(), [
             'current_pw' => 'required',
             'new_pw' => 'required|confirmed|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'
        
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong!');
            return redirect()->back()->withErrors($validator);

        }

        $user = $request->session()->get('user');
            
        $current_pw = $request->current_pw;

        if(!\Hash::check($current_pw, $user->password)){

            alert()->info("Wrong Current Password!");

            return redirect()->back();
        }

        $has_new_pw = \Hash::make($request->new_pw);

        $user->password = $has_new_pw;

        $user->save();

        alert()->success('Successfully Changed!');

        return redirect()->route('Admin.shoplists');
    }
}