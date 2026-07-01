<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcommerceUploadController extends Controller
{
    private array $dirs = [
        'PROOF_OF_PAYMENT' => 'files/proof-of-payments',
        'APPLICATION'      => 'files/applications',
        'BRAND'            => 'files/brands',
    ];

    public function store(Request $request)
    {
        $key = $request->header('X-Upload-Key') ?? $request->input('upload_key');

        if (! $key || $key !== config('app.ecommerce_upload_key')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'type' => 'required|string',
        ]);

        $uploaded = $request->file('file');
        $ext      = strtolower($uploaded->getClientOriginalExtension());
        $type     = strtoupper($request->input('type'));
        $dir      = $this->dirs[$type] ?? 'files/other';
        $filename = $dir . '/' . $type . '-' . uniqid() . '.' . $ext;

        Storage::disk('public_uploads')->putFileAs(
            $dir,
            $uploaded,
            basename($filename)
        );

        return response()->json([
            'file' => $filename,
            'url'  => url($filename),
        ]);
    }
}
