<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadBomRequest;
use App\Services\BomUploadService;
use App\Imports\BomImport;
use Maatwebsite\Excel\Facades\Excel;

class BomController extends Controller
{
    public function store(UploadBomRequest $request, BomUploadService $service)
    {
        $bom = $service->upload($request);

        return response()->json([
            'message' => 'BOM uploaded successfully',
            'data' => $bom,
        ]);
    }
}
