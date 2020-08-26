<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Routing\Controller;
use Laratalk\Laratalk;

class LaratalkController extends Controller
{
    public function __invoke()
    {
        return view('laratalk::layout', [
            'scripts' => Laratalk::scriptBuild()
        ]);
    }
}
