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
        $bom = $service->upload($request);

        return response()->json([
            'message' => 'BOM uploaded successfully',
            'data' => $bom,
        ]);
    }

    public function show($id) {
        $bom = BomHeader::findOrFail($id);

        $items = BomLineItem::where('bom_header_id', $id)
            ->orderBy('id')
            ->paginate(15);

        return view('bom.show', compact('bom', 'items'));
    }
}
