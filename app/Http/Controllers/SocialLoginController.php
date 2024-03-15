<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public $key = "gorilla_key";

    public function genToken($id, $name)
    {
        $payload = array(
            "iss" => "gorilla",
            "aud" => $id,
            "lun" => $name,
            "iat" => Carbon::now()->timestamp,
            // "exp" => Carbon::now()->timestamp + 86400,
            "exp" => Carbon::now()->timestamp + 31556926,
            "nbf" => Carbon::now()->timestamp,
        );

        $token = JWT::encode($payload, $this->key, 'HS256');
        return $token;
    }

    public function login(Request $request)
    {
        if (!isset($request->social_type)) {
            return $this->returnError('ไม่มีข้อมูล');
        } else if (!isset($request->social_id)) {
            return $this->returnError('ไม่มีข้อมูลของผู้ใช้นี้');
        }

        $user = Influencer::query();
        $user = $user
            ->where('social_type', $request->social_type)
            ->where('social_id', ($request->social_id))
            ->where('status', true)
            ->first();

        if ($user) {
            return response()->json([
                'code' => '200',
                'status' => true,
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'data' => $user,
                'token' => $this->genToken($user->id, $user),
            ], 200);
        } else {
            $User = new Influencer();
            $User->social_type = $request->social_type;
            $User->social_id = $request->social_id;
            $User->status = true;
            $User->save();
        }
    }
}