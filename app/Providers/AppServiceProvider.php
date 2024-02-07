<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\facades\view;
use Illuminate\View\View as Myview;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // View::composor('home',function(Myview $view){
         //   return $view->with(['myvar'=>'this is the global variable']);


    }
}
