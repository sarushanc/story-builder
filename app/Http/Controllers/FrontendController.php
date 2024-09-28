<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        try {
            return view('frontend.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to fetch this page: ' . $e->getMessage());
        }
    }
}
