<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseIntentController extends Controller
{
    public function index()
    {
        $intents = PurchaseIntent::latest()->get();

        return view('purchase-intents.index', compact('intents'));
    }
}
