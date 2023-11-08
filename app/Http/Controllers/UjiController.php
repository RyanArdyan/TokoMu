<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UjiController extends Controller
{
    public function index()
    {
        return view('uji.index');
    }
}
