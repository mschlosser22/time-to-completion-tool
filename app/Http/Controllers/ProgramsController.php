<?php

namespace App\Http\Controllers;
use App\Program;
use App\School;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(School $school)
    {
        $programs = Program::where('school_id', $school->id)->get();
        return $programs;
    }

    public function show(Program $program)
    {
        $selectedprogram = Program::where('id', $program->id)->get();
        return $selectedprogram;
    }

}
