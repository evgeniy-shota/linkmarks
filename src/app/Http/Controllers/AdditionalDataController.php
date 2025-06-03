<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContextCollection;
use App\Models\Context;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdditionalDataController extends Controller
{
    public function allContexts()
    {
        $contexts = Context::where('user_id', Auth::id())->get();

        return new ContextCollection($contexts);
    }
}
