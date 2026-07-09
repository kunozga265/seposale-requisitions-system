<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index()
    {
        return Inertia::render('Members/Index', [
            'members' => Member::orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Members/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'body'     => 'required|string',
            'photo'    => 'required|image|max:4096',
            'facebook' => 'nullable|url',
            'twitter'  => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        $photo = $this->uploadPhoto($request->file('photo'));

        Member::create([
            'name'     => $request->name,
            'slug'     => Str::slug($request->name) . date('Y-m-d'),
            'position' => $request->position,
            'body'     => $request->body,
            'photo'    => $photo,
            'links'    => json_encode([
                'facebook' => $request->facebook ?? '',
                'twitter'  => $request->twitter ?? '',
                'linkedin' => $request->linkedin ?? '',
            ]),
        ]);

        return Redirect::route('members.index')->with('success', 'Team member added.');
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);

        return Inertia::render('Members/Edit', [
            'member' => $member,
            'links'  => json_decode($member->links, true),
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'body'     => 'required|string',
            'photo'    => 'nullable|image|max:4096',
            'facebook' => 'nullable|url',
            'twitter'  => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        $photo = $member->photo;
        if ($request->hasFile('photo')) {
            if ($photo && file_exists(public_path($photo))) {
                Storage::disk('public_uploads')->delete($photo);
            }
            $photo = $this->uploadPhoto($request->file('photo'));
        }

        $member->update([
            'name'     => $request->name,
            'position' => $request->position,
            'body'     => $request->body,
            'photo'    => $photo,
            'links'    => json_encode([
                'facebook' => $request->facebook ?? '',
                'twitter'  => $request->twitter ?? '',
                'linkedin' => $request->linkedin ?? '',
            ]),
        ]);

        return Redirect::route('members.index')->with('success', 'Team member updated.');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        if ($member->photo && file_exists(public_path($member->photo))) {
            Storage::disk('public_uploads')->delete($member->photo);
        }

        $member->delete();

        return Redirect::route('members.index')->with('success', 'Team member deleted.');
    }

    private function uploadPhoto($file): string
    {
        $ext      = strtolower($file->getClientOriginalExtension());
        $filename = 'images/members/member-' . uniqid() . '.' . $ext;

        Storage::disk('public_uploads')->putFileAs(
            'images/members',
            $file,
            basename($filename)
        );

        return $filename;
    }
}
