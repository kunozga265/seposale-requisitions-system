<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Policies/Index', [
            'policies' => $policies,
        ]);
    }

    public function create()
    {
        return Inertia::render('Policies/Create', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'sort_order' => 'integer|min:0',
            'active'     => 'boolean',
        ]);

        Policy::create([
            'title'      => $request->title,
            'slug'       => $this->uniqueSlug($request->title),
            'body'       => $request->body,
            'sort_order' => $request->sort_order ?? 0,
            'active'     => $request->boolean('active', true),
        ]);

        return Redirect::route('policies.index')->with('success', 'Policy created!');
    }

    public function edit(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        return Inertia::render('Policies/Edit', [
            'policy' => $policy,
        ]);
    }

    public function update(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'sort_order' => 'integer|min:0',
            'active'     => 'boolean',
        ]);

        $policy->update([
            'title'      => $request->title,
            'slug'       => $request->title === $policy->title ? $policy->slug : $this->uniqueSlug($request->title, $policy->id),
            'body'       => $request->body,
            'sort_order' => $request->sort_order ?? 0,
            'active'     => $request->boolean('active', true),
        ]);

        return Redirect::route('policies.index')->with('success', 'Policy updated!');
    }

    public function destroy($id)
    {
        Policy::findOrFail($id)->delete();

        return Redirect::route('policies.index')->with('success', 'Policy deleted!');
    }

    private function uniqueSlug(string $source, $ignoreId = null)
    {
        $base = Str::slug($source);
        $slug = $base;
        $suffix = 2;

        while (Policy::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
