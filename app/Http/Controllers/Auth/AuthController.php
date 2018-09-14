<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use JWTAuth;
use JWTAuthException;

class AuthController extends Controller
{
    public function index() {
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }


    public function employeeSignUp(Request $request) {
        $rules = [
            'fullName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'profile_picture_url' => 'required',
            'phone' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $user = new User([
            'fullName' => $data['fullName'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'profile_picture_url' => $data['profile_picture_url'],
            'phone' => $data['phone'],
            'role' => 'karyawan',
            'status' => 'active',
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];


        if($user->save()){

            $token = null;
            try{
                if(!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'msg' => 'Email or Password are incorrect',
                    ], 404);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'msg' => 'failed_to_create_token',
                ],404);
            }

            $user->signin = [
                'href' => '/api/session/employee-signup',
                'method' => 'POST',
                'params' => 'email, password'
            ];
            $response = [
                'msg' => 'User created',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];
            return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occurred'
        ];

        return response()->json($response, 404);
    }

    public function employeeLogin(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ];

        $this->validate($request, $rules);

        $email = $request->input('email');
        $password = $request->input('password');

        if($user = User::where('email', $email)->first()){
            $credentials = [
                'email' => $email,
                'password' => $password
            ];

            $token = null;

            try{
                if(!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'msg' => 'Email or password are incorrect',
                    ], 404);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'msg' => 'failed_to_create_token'
                ],404);
            }

            $response = [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];

            return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occured'
        ];

        return response()->json($response, 404);
    }


    public function destroy($id) {

    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    public function storeTest(Request $request){
        $rules = [
            'fullName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'profile_picture_url' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'status' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return response()->json(['data' => $user], 200);
    }

    public function employeeProfile()
    {
        auth()->user();
        $response = [
            'fullName' => auth()->user()->fullName,
            'phone' => auth()->user()->phone,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'status' => auth()->user()->status,
            'profile_picture_url' => auth()->user()->profile_picture_url,
            'created_at' => (string) auth()->user()->created_at,
            'updated_at' => (string) auth()->user()->updated_at,
        ];

        return response()->json($response, 200);

    }
}
