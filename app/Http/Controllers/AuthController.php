<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function auth(Request $request) {
        try {
            $authHeader = base64_decode(explode(' ', $request->header('Authorization'))[1]);
            
            $nim = explode(':', $authHeader)[0];
            $password = explode(':', $authHeader)[1];
            $passport = md5('123ab' . $password) . '_' . $nim;
            $ip = $_SERVER['REMOTE_ADDR'];

            $url_login = "https://bais.ub.ac.id/api/login/jsonapi/?userid=$nim&passport=$passport&challenge=123ab&appid=EKS1&ipaddr=$ip";
            $initlogin = curl_init($url_login);
            curl_setopt($initlogin, CURLOPT_URL, $url_login);
            curl_setopt($initlogin, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($initlogin, CURLOPT_SSL_VERIFYPEER, false);
            $execlogin = curl_exec($initlogin);

            return response()->json([
                'message' => 'success',
                'data' => base64_encode($execlogin)
            ], 200);
        } catch (Exception $ex) {
            report($ex);
            return response()->json([
                'message' => 'error',
                'data' => $ex->getMessage()
            ], 400);
        }
    }
}
