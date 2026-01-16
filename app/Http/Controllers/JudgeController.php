<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JudgeController extends Controller
{
    public function dashboard() {
        $submissionsToReview = []; // Placeholder for submissions to review
        return view('judge.dashboard', compact('submissionsToReview'));
    }
}
