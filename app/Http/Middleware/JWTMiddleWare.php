<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleWare
{
    public function handle(Request $request, Closure $next)
    {
        if (is_null($request->bearerToken())) {
            return response()->json(['error' => 'Token required.'], 401);
        }
        try {
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 401);
        }
        return $next($request);
    }
}
