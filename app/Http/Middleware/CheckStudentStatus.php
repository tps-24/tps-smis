<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStudentStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->student && $user->student->status === 'pending') {
            return redirect()->route('dashboard')->with('pending_message', 'Welcome!...Your account is pending for approval.');
        }

        return $next($request);
    }
}
