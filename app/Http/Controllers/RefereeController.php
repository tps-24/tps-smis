<?php

namespace App\Http\Controllers;

use App\Models\Referee;
use Illuminate\Http\Request;

class RefereeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'referee_fullname' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $request->user()->referees()->create($request->all());

        return redirect()->back()->with('success', 'Referee added successfully!');
    }

    public function destroy($id)
    {
        $referee = Referee::findOrFail($id);
        $referee->delete();

        return redirect()->back()->with('success', 'Referee removed successfully!');
    }
}

