<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            return view('home');
    }
    public function index2()
    {
            return view('home2');
    }



    public function roles(){
        return view('roles.roles');
    }

    public function permissions(){
        return view('roles.permissions');
    }
    public function users(){
        return view('roles.user');
    }
}
