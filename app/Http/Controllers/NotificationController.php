<?php

namespace App\Http\Controllers;
use App\Models\Announcement;
use App\Models\SharedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $category, string $type, string $id, string $ids)
    {
        //if($category == "announcement"){
            $notification = Announcement::find($id);
        //}

        return view('notifications.view', compact('notification', 'ids'));
    }

    /**
     * Show the form for editing the specified resource.
     */

     public function showNotifications($Ids, $category)
     {

           $notification = SharedNotification::whereIn('id',[$Ids])->get()[0]; 

         return view('notifications.view', compact('notification','category'));
     }
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


public function markAsRead(Request $request, $id)
{
    $user = $request->user();

    // Ensure the notification belongs to the user to prevent unauthorized access
    $notification = $user->sharedNotifications()->find($id);

    if (!$notification) {
        return response()->json(['error' => 'Notification not found.'], 404);
    }

    // Update the pivot table
    $user->sharedNotifications()->updateExistingPivot($id, ['read_at' => now()]);

    return response()->json(['status' => 'read']);
}

}
