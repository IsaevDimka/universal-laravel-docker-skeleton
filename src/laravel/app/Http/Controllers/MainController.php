<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
