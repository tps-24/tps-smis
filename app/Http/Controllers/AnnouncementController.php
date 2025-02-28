<?php

namespace App\Http\Controllers;
use App\Events\NotificationEvent;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AnnouncementController extends Controller
{
    private $selectedSessionId;
    public function __construct()
    {
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId)
            $this->selectedSessionId = 1;

        $this->middleware('permission:announcement-list|announcement-create|announcement-edit', ['only' => ['index', 'show', 'edit']]);
        $this->middleware('permission:attendance-create', ['only' => ['create', 'store', 'update']]);
        $this->middleware('permission:announcement-delete', ['only' => ['destroy']]);


    }
    public function index()
    {
        $announcements = Announcement::where('expires_at', '>', Carbon::now())->orderBy('created_at', 'desc')->get();
        broadcast(new NotificationEvent($announcements[0]->title, $announcements[0]->type, 'announcement', $announcements[0], $announcements[0]->id));
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
            'document' => 'required|mimes:pdf|max:5120',//5MB
            'type' => 'required',
            'audience' => 'required',
            'expires_at' => 'nullable|date',
        ]);

        $expiresAt = $request->input('expires_at');
        if ($expiresAt) {
            $expiresAt = Carbon::createFromFormat('Y-m-d\TH:i', $expiresAt);
        }
        foreach (Auth::user()->roles as $role) { {
                $request->audience = Auth::user()->staff->company_id;
            }
        }
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->message = $request->message;
        $announcement->type = $request->type;
        $announcement->posted_by = $request->user()->id;
        $announcement->expires_at = $expiresAt;

        if ($file = $request->file('document')) {
            $filePath = $file->store('uploads', 'public');
            $announcement->document_path = $filePath;
        }

        if ($request->audience == "all") {
            $announcement->audience = $request->audience;
        } else {
            $announcement->company_id = Auth::user()->staff->company_id;
        }
        $announcement->save();
        broadcast(new NotificationEvent($announcement->title, $announcement->type, 'announcement', $announcement, $announcement->id));
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

    public function downloadFile ($announcementId) {
        $announcement = Announcement::find($announcementId);
        $path = storage_path('app/public/' . $announcement->document_path);
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
    
}
