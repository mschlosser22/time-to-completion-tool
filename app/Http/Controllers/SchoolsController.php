<?php

namespace App\Http\Controllers;
use App\School;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{
    
	public function index()
    {
        return School::all();
    }

    public function show(School $school)
    {
        $selectedschool = School::where('id', $school->id)->get();
        return $selectedschool;
    }

}
