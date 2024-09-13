<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveOwnershipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('archive/*')) {
            $archiveId = $request->route('archives');
            $userId = auth()->id();
            $archive = \App\Models\Archive::find($archiveId);

            if ($archive && $archive->user_id !== $userId) {
                return redirect('/dashboard')->with('error', 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
