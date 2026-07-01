<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('variant.product')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($r) {
                return [
                    'id'          => $r->id,
                    'variant'     => $r->variant ? [
                        'id'      => $r->variant->id,
                        'name'    => $r->variant->name ?: $r->variant->description,
                        'product' => $r->variant->product?->name,
                    ] : null,
                    'name'        => $r->name,
                    'rating'      => $r->rating,
                    'comment'     => $r->comment,
                    'location'    => $r->location,
                    'verified'    => $r->verified,
                    'helpfulCount'=> $r->helpful_count,
                    'photos'      => $r->photos ?? [],
                    'active'      => $r->active,
                    'date'        => (int) $r->date,
                ];
            });

        $variants = ProductVariant::with('product')->get()->map(fn($v) => [
            'id'      => $v->id,
            'name'    => ($v->product?->name ? $v->product->name . ' — ' : '') . ($v->name ?: $v->description),
        ]);

        return Inertia::render('Reviews/Index', [
            'reviews'  => $reviews,
            'variants' => $variants,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'name'               => 'required|string|max:255',
            'rating'             => 'required|integer|min:1|max:5',
            'comment'            => 'nullable|string',
            'verified'           => 'boolean',
            'location'           => 'nullable|string|max:255',
            'helpful_count'      => 'integer|min:0',
            'active'             => 'boolean',
        ]);

        Review::create([
            'product_variant_id' => $request->product_variant_id,
            'name'               => $request->name,
            'rating'             => $request->rating,
            'comment'            => $request->comment,
            'date'               => now()->timestamp,
            'verified'           => $request->boolean('verified'),
            'location'           => $request->location,
            'helpful_count'      => $request->helpful_count ?? 0,
            'active'             => $request->boolean('active'),
        ]);

        return Redirect::back()->with('success', 'Review added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'sometimes|string|max:255',
            'rating'        => 'sometimes|integer|min:1|max:5',
            'comment'       => 'nullable|string',
            'verified'      => 'sometimes|boolean',
            'location'      => 'nullable|string|max:255',
            'helpful_count' => 'sometimes|integer|min:0',
            'active'        => 'sometimes|boolean',
            'featured'      => 'sometimes|boolean',
        ]);

        Review::findOrFail($id)->update($request->only(
            'name', 'rating', 'comment', 'verified', 'location', 'helpful_count', 'active', 'featured'
        ));

        return Redirect::back()->with('success', 'Review updated.');
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return Redirect::back()->with('success', 'Review deleted.');
    }
}
