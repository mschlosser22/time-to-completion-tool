<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;

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
     * @return \Illuminate\Http\Response
     */
    // All Schools
    public function index(School $school) {

        $schools = School::all();
        return view('home', compact('schools'));

    }
};
