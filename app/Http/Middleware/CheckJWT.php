<?php

namespace App\Http\Middleware;

use App\Models\Influencer;
use App\Models\User;
use Closure;
use Exception;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public $key = "gorilla_key";

    public function handle($request, Closure $next)
    {
        try {
            $header = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $header);

            if (!$token) {
                return response()->json([
                    'code' => '401',
                    'status' => false,
                    'message' => 'Token Not Found',
                    'data' => [],
                ], 401);
            }

            $payload = JWT::decode($token, new Key($this->key, 'HS256'));
            // $payload = JWT::decode($token, $this->key, array('HS256'));
            $request->login_id = $payload->aud;

            $user = Influencer::where('id',  $payload->aud)
                ->first();

            $request->login_by = $user;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                'code' => '401',
                'status' => false,
                'message' => 'Token is expire',
                'data' => [],
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'code' => '401',
                'status' => false,
                'message' => 'Can not verify identity',
                'data' => [],
            ], 401);
        }

        return $next($request);
    }
}
