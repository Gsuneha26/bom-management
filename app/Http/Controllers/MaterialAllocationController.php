<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialAllocation;

class MaterialAllocationController extends Controller
{
   public function index()
    {
        $allocations = MaterialAllocation::latest()->paginate(20);

        return view(
            'allocations.index',
            compact('allocations')
        );
    }
}
