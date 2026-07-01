<?php

namespace App\Http\Controllers;

use App\Models\BuildingTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BuildingTipController extends Controller
{
    public function index()
    {
        $tips = BuildingTip::orderByDesc('id')->get();

        return Inertia::render('BuildingTips/Index', [
            'tips' => $tips,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'category' => 'nullable|string|max:100',
            'photo'    => 'nullable|string|max:500',
            'active'   => 'boolean',
        ]);

        BuildingTip::create([
            'title'    => $request->title,
            'body'     => $request->body,
            'category' => $request->category,
            'photo'    => $request->photo,
            'active'   => $request->boolean('active', true),
        ]);

        return Redirect::back()->with('success', 'Building tip added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'sometimes|string|max:255',
            'body'     => 'sometimes|string',
            'category' => 'nullable|string|max:100',
            'photo'    => 'nullable|string|max:500',
            'active'   => 'sometimes|boolean',
        ]);

        BuildingTip::findOrFail($id)->update($request->only('title', 'body', 'category', 'photo', 'active'));

        return Redirect::back()->with('success', 'Building tip updated.');
    }

    public function destroy($id)
    {
        BuildingTip::findOrFail($id)->delete();

        return Redirect::back()->with('success', 'Building tip deleted.');
    }
}
