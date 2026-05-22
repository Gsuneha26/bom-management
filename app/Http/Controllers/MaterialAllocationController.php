<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialAllocation;

class MaterialAllocationController extends Controller
{
   public function index()
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Store Manager'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access Allocations.');
        }

        $allocations = MaterialAllocation::latest()->paginate(20);

        return view(
            'allocations.index',
            compact('allocations')
        );
    }
}
