<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
		use AuthenticatesUsers;

	public function login(Request $request)
	{
		$data = $request->json()->all();
		$user = User::where('email', $data['email'])->first();

		if($user != null && Hash::check($data['password'], $user->getAuthPassword()))
		{
			$user->remember_token = Str::random(60);
			$user->save();

			$response_data = [
				"status_code" => 200,
			        "auth_token" => $user->remember_token,
				"user" => [
					"id" => $user->id,
					"name" => $user->name,
					"email" => $user->email,
					"rola_id" => $user->rola_id
				]	
			];
			return response()->json($response_data);
		}

		else {
			$response_data = [
				"status_code" => 400,
				"auth_token" => '',
				"user" => [
					"id" => "0",
					"name" => "",
					"email" => "",
					"rola_id" => "2"	
				]
			];
			return response()->json($response_data);
		}	
	}
}
