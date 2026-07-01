<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\ProductVariantReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductVariantRewardController extends Controller
{
    public function index(Request $request)
    {
        $variants = ProductVariant::with(['product', 'reward'])->get()->map(function ($variant) {
            return [
                'id'           => $variant->id,
                'name'         => $variant->name ?: $variant->description,
                'product'      => $variant->product ? $variant->product->name : null,
                'rewardId'     => $variant->reward?->id,
                'rewardAmount' => $variant->reward?->reward_amount,
                'active'       => $variant->reward?->active ?? false,
                'hasReward'    => $variant->reward !== null,
            ];
        });

        return Inertia::render('Rewards/ProductRewards', [
            'variants' => $variants,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'reward_amount'      => 'required|numeric|min:0.01',
        ]);

        ProductVariantReward::updateOrCreate(
            ['product_variant_id' => $request->product_variant_id],
            ['reward_amount' => $request->reward_amount, 'active' => true]
        );

        return Redirect::back()->with('success', 'Reward saved.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reward_amount' => 'sometimes|numeric|min:0.01',
            'active'        => 'sometimes|boolean',
        ]);

        $reward = ProductVariantReward::findOrFail($id);
        $reward->update($request->only('reward_amount', 'active'));

        return Redirect::back()->with('success', 'Reward updated.');
    }

    public function destroy($id)
    {
        ProductVariantReward::findOrFail($id)->delete();
        return Redirect::back()->with('success', 'Reward removed.');
    }
}
