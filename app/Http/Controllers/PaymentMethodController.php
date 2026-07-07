<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentMethodController extends Controller
{
    public function index(): Response
    {
        $methods = PaymentMethod::orderBy('name')->get()->map(fn ($m) => [
            'id'               => $m->id,
            'name'             => $m->name,
            'photo'            => $m->photo,
            'for_withdrawal'   => (bool) $m->for_withdrawal,
            'withdrawal_fields' => $m->withdrawal_fields ?? [],
        ]);

        return Inertia::render('PaymentMethods/Index', [
            'paymentMethods' => $methods,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'for_withdrawal'   => 'boolean',
            'withdrawal_fields' => 'nullable|array',
            'withdrawal_fields.*.key'      => 'required|string|max:50',
            'withdrawal_fields.*.label'    => 'required|string|max:100',
            'withdrawal_fields.*.type'     => 'required|in:text,tel,number,email',
            'withdrawal_fields.*.required' => 'boolean',
        ]);

        $method->update([
            'for_withdrawal'   => $request->boolean('for_withdrawal'),
            'withdrawal_fields' => $request->withdrawal_fields ?: null,
        ]);

        return back()->with('success', 'Payment method updated.');
    }
}
