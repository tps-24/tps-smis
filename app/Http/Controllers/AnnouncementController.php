<?php

namespace App\Http\Controllers;
use App\Events\MessageSent;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        broadcast(new MessageSent($announcements[0]));
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'required',
            'expires_at' => 'nullable|date',
        ]);

        $expiresAt = $request->input('expires_at');
        if ($expiresAt) {
            $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $expiresAt);
        }
       $announcement = Announcement::create(
            [
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'posted_by'=> Auth::user()->id,
                'expires_at' => $expiresAt,
            ]
        );
        broadcast(new MessageSent($announcement));
        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'required',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($request->all());
        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
