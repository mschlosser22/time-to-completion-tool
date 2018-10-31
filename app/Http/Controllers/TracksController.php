<?php

namespace App\Http\Controllers;
use App\School;
use App\Program;
use App\Track;
use Illuminate\Http\Request;

class TracksController extends Controller
{

    public function index(Program $program)
    {
        $tracks = Track::where('program_id', $program->id)->get();
        return $tracks;
    }

    public function show(Track $track)
    {
        $selectedtrack = Track::where('id', $track->id)->get();
        return $selectedtrack;
    }

}
