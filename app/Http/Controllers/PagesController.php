<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function aboutUs()
    {
        
        //return public_path('/favicon.ico');
        //return url('/favicon.ico');
        
        //return route('front.pages.faq');
        
        
        //return \Str::random(32);
        
        //$test = 32.45;
        
        //dd($test);
        
        //return \Str::slug('~Save your time @ ^^^ .xlsx');
        
        //return bcrypt('cubes123');
        
        //return __('Hello world');
        //return trans('Hello world');
        
        return view('front.pages.about_us');// resources/views/front/pages/about_us.balde.php
    }
    
    public function faq()
    {
        return view('front.pages.faq');
    }
}
