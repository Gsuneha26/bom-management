<?php

namespace App\Http\Controllers;

use App\Models\PurchaseIntent;
use Illuminate\Http\Request;

class PurchaseIntentController extends Controller
{
    public function index()
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Purchase Dept'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access Purchase Intents.');
        }

        $intents = PurchaseIntent::latest()->get();

        return view('purchase-intents.index', compact('intents'));
    }
}
