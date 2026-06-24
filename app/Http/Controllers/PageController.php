<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function privacy()
    {
        return view('page.confidentialite');
    }

    public function legal()
    {
        return view('page.mentions-legales');
    }
}
