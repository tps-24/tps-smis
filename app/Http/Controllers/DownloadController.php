<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Download;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    // Show all downloads
    public function index()
    {
        $downloads = Download::latest()->get();
        return view('downloads.index', compact('downloads'));
    }

    // Show upload form (Now accessible to all users)
    public function create()
    {
        return view('downloads.create');
    }

    // Store uploaded file
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:20480', // Max 20MB
            'category' => 'required|string'
        ]);

        $filePath = $request->file('file')->store('uploads', 'public');

        Download::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'category' => $request->category,
            'uploaded_by' => Auth::id() // Save user ID who uploaded the file
        ]);

        return redirect()->route('downloads.index')->with('success', 'File uploaded successfully.');
    }

    // Download file
    public function download($file)
    {
        return Storage::disk('public')->download("uploads/{$file}");
    }
}
