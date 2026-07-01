<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Faqs/Index', [
            'faqs' => $faqs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'integer|min:0',
            'active'     => 'boolean',
        ]);

        Faq::create([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'category'   => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'active'     => $request->boolean('active', true),
        ]);

        return Redirect::back()->with('success', 'FAQ added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question'   => 'sometimes|string|max:500',
            'answer'     => 'sometimes|string',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'sometimes|integer|min:0',
            'active'     => 'sometimes|boolean',
        ]);

        Faq::findOrFail($id)->update($request->only('question', 'answer', 'category', 'sort_order', 'active'));

        return Redirect::back()->with('success', 'FAQ updated.');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();

        return Redirect::back()->with('success', 'FAQ deleted.');
    }
}
