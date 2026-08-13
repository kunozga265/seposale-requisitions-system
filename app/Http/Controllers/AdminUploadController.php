<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Same shared-key-authenticated, no-session-required upload proxy as
 * EcommerceUploadController, kept as its own controller (own key, own
 * route) so the two callers' credentials can be rotated independently --
 * mirrors this codebase's existing per-caller-controller convention rather
 * than a single generic multi-key endpoint. Backs admin's
 * FILE_STORAGE_CURRENT_SERVER=false mode (uploads stored on system until
 * admin fully takes over).
 */
class AdminUploadController extends Controller
{
    private array $dirs = [
        'PROOF_OF_PAYMENT' => 'files/proof-of-payments',
        'APPLICATION'      => 'files/applications',
        'BRAND'            => 'files/brands',
        'BUILDING_TIP'     => 'files/building-tips',
        'PRODUCT'          => 'files/products',
        'PRODUCT_VARIANT'  => 'files/product-variants',
        'MEMBER'           => 'files/members',
        'HERO_SLIDE'       => 'files/hero-slides',
        'DELIVERY_NOTE'    => 'files/deliveries',
        'COLLECTION_NOTE'  => 'files/collections',
    ];

    public function store(Request $request)
    {
        $key = $request->header('X-Upload-Key') ?? $request->input('upload_key');

        Log::info('AdminUploadController::store() called', [
            'key' => $key,  
            'upload_key_config' => config('app.admin_upload_key'),
        ]);

        if (! $key || $key !== config('app.admin_upload_key')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            // gif/webp added -- confirmed via a real prod failure that these
            // (plausible from a device's photo gallery, unlike the original
            // jpg/jpeg/png/pdf/doc/docx-only list) were being rejected here,
            // which admin/'s FileUploadService::storeRemotely() surfaced as
            // an opaque "Return value must be of type string, null
            // returned" crash rather than this validation message, since
            // that caller wasn't sending Accept: application/json (fixed
            // separately in admin/).
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx',
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
