<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class Connect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request has a '_token' header
        if ($request->hasHeader('session_token') || $request->has('session_token')) {
            $token = $request->header('session_token') ?? $request->session_token;
            $tokenObj = ApiToken::where('_token',$token)->first();
            // Validate the token
            if (!empty($tokenObj)) {
                $tokenObj->delete();
                return $next($request);
            }
        }

        // If token is missing or invalid, return an unauthorized response
        return response()->json([
            'message' => 'Unauthorized'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
