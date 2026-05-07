<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function show(string $slug): View
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();
        return view('pages.activities.show', compact('activity'));
    }
}
