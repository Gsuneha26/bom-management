<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadBomRequest;
use App\Services\Bom\BomUploadService;
use App\Models\BomHeader;

class BomApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => BomHeader::latest()->paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bom = BomHeader::with('items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bom,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UploadBomRequest $request,
        BomUploadService $service)
    {
        $bom = $service->upload($request);

        return response()->json([
            'success' => true,
            'message' => 'BOM uploaded successfully',
            'data' => $bom,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function status($id)
    {
        $bom = BomHeader::findOrFail($id);

        return response()->json([
            'status' => $bom->status,
        ]);
    }
}
