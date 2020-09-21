<?php

namespace App\Http\Controllers;

class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function __invoke()
    {
        return view('spa');
    }
}
