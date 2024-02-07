<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Middleware\AuthenticateSession;


Route::prefix('admin')->name('admin.')->group(function(){

    Route::middleware(['guest:admin','PreventBackHistory'])->group(function(){
  Route::view('/login','back.pages.admin.auth.login')->name('login');
  Route::post('/login_handler',[AdminController::class,'loginHandler'])->name('login_handler');
Route::view('/forgot-password','back.pages.admin.auth.forgot-password')->name('forgot-password');
Route::post('/send-password-reset-link',[AdminController::class,'sendpasswordresetlink'])->name('send-password-reset-link');
Route::get('/password/reset/{token}',[AdminController::class,'passwordreset'])->name('reset-password');
Route::get('reset-password-handler',[AdminController::class,'resetpasswordhandler'])->name('reset-password-handler');

    });
    Route::middleware(['auth:admin','PreventBackHistory'])->group(function(){
        Route::view('/home','back.pages.admin.home')->name('home');
        Route::any('/logout_handler',[AdminController::class,'logouthandler'])->name('logout_handler');
        //session()->put('fail','you are logged in!');




    });

});
Route::get('set/session',function(){
  session()->put('fail','Incorrect Credentials');

});
Route::get('get/session',function(){
 // Session()->get('fail');
if(session()->has('fail')){
  return session('fail');
}

});
