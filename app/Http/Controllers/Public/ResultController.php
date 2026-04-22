<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ResultPublication;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display the public results for a publication code.
     */
    public function show(string $code)
    {
        $publication = ResultPublication::with(['event', 'category'])
            ->where('publication_code', $code)
            ->where('status', 'published')
            ->firstOrFail();

        return view('public.results.show', compact('publication'));
    }
}

