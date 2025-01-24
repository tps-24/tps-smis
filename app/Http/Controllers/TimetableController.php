<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::orderBy('company')->orderBy('day')->orderBy('start_time')->get();
        return view('timetable.index', compact('timetables'));
    }

    public function create()
    {
        return view('timetable.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string',
            'day' => 'required|string',
            'venue' => 'required|string',
            'subject' => 'required|string',
            'teacher' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Timetable::create($request->all());

        return redirect()->route('timetable.index')->with('success', 'Timetable entry added successfully.');
    }

    public function edit(Timetable $timetable)
    {
        return view('timetable.edit', compact('timetable'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'company' => 'required|string',
            'day' => 'required|string',
            'venue' => 'required|string',
            'subject' => 'required|string',
            'teacher' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $timetable->update($request->all());

        return redirect()->route('timetable.index')->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('timetable.index')->with('success', 'Timetable entry deleted successfully.');
    }

    public function generatePdf($company)
    {
        $timetables = Timetable::where('company', $company)->orderBy('day')->orderBy('start_time')->get();

        $pdf = Pdf::loadView('timetable.pdf', compact('timetables', 'company'));

        return $pdf->download("timetable_{$company}.pdf");
    }
}
