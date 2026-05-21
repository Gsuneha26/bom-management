<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadBomRequest;
use App\Services\BomUploadService;
use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Imports\BomImport;
use Maatwebsite\Excel\Facades\Excel;

class BomController extends Controller
{
    public function index()
    {
        $boms = BomHeader::latest()
            ->withCount('items')
            ->get();

        return view('dashboard', compact('boms'));
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
            ->get();

        return view('bom.show', compact('bom', 'items'));        
    }
}
