<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function dashboard() {
        return view('participant.dashboard', ['submissions' => [], 'competitions' => []]);
    }
}
