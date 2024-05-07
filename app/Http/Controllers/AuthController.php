<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['auth_token'] =  $user->createToken('MyTaskTracker')->plainTextToken;

        return $this->sendResponse($success, 'User register successfully.');
    }


    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['auth_token'] =  $user->createToken('MyTaskTracker')->plainTextToken;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->register($request);
        }
    }
}
