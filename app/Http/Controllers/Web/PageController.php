<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View   { return view('pages.about'); }
    public function contact(): View { return view('pages.contact'); }
    public function offers(): View  { return view('pages.offers'); }
    public function landing(): View { return view('pages.landing'); }
}
