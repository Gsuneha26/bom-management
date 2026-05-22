<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadBomRequest;
use App\Services\Bom\BomUploadService;
use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Models\PurchaseIntent;
use App\Models\MaterialAllocation;
use App\Imports\BomImport;
use Maatwebsite\Excel\Facades\Excel;

class BomController extends Controller
{
    public function index()
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Engineer', 'Store Manager', 'Purchase Dept'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the dashboard.');
        }

        $boms = BomHeader::latest()
            ->withCount('items')
            ->get();

        $totalBoms = BomHeader::count();
        $pendingIntents = PurchaseIntent::where('status', 'Pending')->count();
        $allocationsMade = MaterialAllocation::count();

        return view('dashboard', compact('boms', 'totalBoms', 'pendingIntents', 'allocationsMade'));
    }

    public function store(UploadBomRequest $request, BomUploadService $service)
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Engineer', 'Store Manager'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to upload a BOM.');
        }

        $bom = $service->upload($request);

        return redirect()
            ->route('bom.show', $bom->id)
            ->with('success', 'BOM uploaded successfully and inventory processing has started.');
    }

    public function show($id) {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Engineer', 'Store Manager'])) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to view this BOM.');
        }

        $bom = BomHeader::findOrFail($id);

        $items = BomLineItem::where('bom_header_id', $id)
            ->orderBy('id')
            ->paginate(15);

        return view('bom.show', compact('bom', 'items'));
    }
}
