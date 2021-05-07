<?php

namespace Mukja\Posty\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestPostyConnectionController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->noContent(200);
    }
}
