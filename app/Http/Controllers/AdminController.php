<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\AuthenticateSession;
use constGuards;
use constDefault;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Admin;
class AdminController extends Controller
{
public function loginHandler(Request $request){
    $failed_type = filter_var($request->login_id,FILTER_VALIDATE_EMAIL) ? 'email':'username';

    if($failed_type == 'email'){
        $request->validate([
            'login_id'=>'required|email|exists:admins,email',
            'password'=>'required|min:5|max:45',
        ],[
            'login_id.required'=>'Email or Username is required',
            'login_id.email'=>'invalid Email address',
            'login_id.exists'=>'Email is not Exists on system',
            'password.required'=>'password is required',

        ]);
    }else{

        $request->validate([
            'login_id'=>'required|email|exists:admins,username',
            'password'=>'required|min:5|max:45',


        ],[
            'login_id.required'=>'Email or Username is required',
            'login_id.exists'=>'username is not Exists on system',
            'password.required'=>'password is required',


        ]);

    }
    $credits = array(
        $failed_type => $request->login_id,
        'password'=>$request->password,
    );
    if (Auth::guard('admin')->attempt($credits)){

    return redirect()->route('admin.home');
    }else{
       session()->flash('fail','Incorrect Credentials');
       return redirect()->route('admin.login');

    }
}
public function logouthandler(Request $request){
    Auth::guard('admin')->logout();
    session()->flash('fail','you are logged out!');
    return redirect()->route('admin.login');
}
public function sendpasswordresetlink(Request $request){

    $request->validate([
        'email'=>'required|email|exists:admins,email'
    ],[
        'email.required'=>'the :attribute is required',
        'email.email'=>'Invalid Email address',
        'email.exists'=>'the :attribute is not exists on system'
    ]);

    //get admin details
    $admin = Admin::where('email',$request->email)->first();
    //Generate token
    $token = base64_encode(Str::random(64));
    //check if exists or not from database field of password token
    $oldtoken = DB::table('password_resets')->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])->first();
    if($oldtoken){
        //update token
        DB::table('password_resets')->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])->update([
         'token'=>$token,
         'created_at'=>Carbon::now()
        ]);
    }else{
        DB::table('password_resets')->insert([
            'email'=>$request->email,
            'guard'=>constGuards::ADMIN,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        $actionlink = route('admin.reset-password',['token'=>$token,'email'=>$request->email]);
        $data = array([
            'actionlink'=>$actionlink,
            'admin'=>$admin,
        ]);
        $mail_body = view('email-templates.admin-forgot-email-template')->render();
        $mail_config = array(
            'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('EMAIL_FROM_NAME'),
            'mail_recipient_email'=>$admin->email,
            'mail_recipient_name'=>$admin->name,
            'mail_subject'=>'Reset password',
            'mail_body'  => $mail_body,

        );
        if(sendEmail($mail_config)){

            session()->flash('success','we have a mailed your password reset link.');
            return redirect()->route('admin.forgot-password');

        }else{
            session()->flash('fail','something was wrong!');
            return redirect()->route('admin.forgot-password');
        }
    }
}
public function passwordreset(Request $request,$token = null){
    $check_token = DB::table('password_resets')->where(['token'=>$token,'guard'=>constGuards::ADMIN])->first();
   if($check_token){
    $difmins = Carbon::createfromformat('y-m-d H:i:s',$check_token->created_at)->diffinMinutes(Carbon::now());
    if($difmins > constDefaults::tokenExpireMinutes){
        session()->flash('fail','token expired , request another reset password link.');
        return redirect()->route('admin.forgot-password',['token'=>$token]);

    }else{
        return view('back.pages.admin.auth.reset-password')->with(['token',$token]);
    }
   }
}
public function resetpasswordhandler(Request $request){

    $request->validate([
      'new-password'=>'required|min:5|max:45|required_with:new-password-confirmation|same:new-password-confirmation',
      'new-password-confirmation'=>'required',
    ]);
    $token = DB::table('password_resets')->where(['token'=>$request->token,'guard'=>constGuard::ADMIN])->first();
    //Get admin details
 $admin = Admin::where('email',$token->email);
   //update admin password
   Admin::where('email',$admin->email)->update([
     'password'=>Hash($request->new_password)
   ]);
   //Delete the token
   DB::table('password_resets')->where([
    'email'=>$admin->email,
    'token'=>$request->token,
    'guard'=>constGuards::ADMIN
   ])->delete();

}
}
