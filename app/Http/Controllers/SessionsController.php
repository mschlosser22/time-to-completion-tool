<?php

namespace App\Http\Controllers;
use App\Session;
use App\School;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    
    public function index(School $school)
    {
        $sessions = Session::where('school_id', $school->id)->get();
        return $sessions;
    }

}
