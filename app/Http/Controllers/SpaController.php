<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return Application|Factory|View
     */
    public function __invoke()
    {
        return view('spa');
    }
}
