<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Dtos\UserDto;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class AuthenticationController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {

    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        // User::query()->findOrFail(123456);

        // User::create([]);

        $userDto = UserDto::fromApiFormRequest($request);
        $user = $this->userService->createUser($userDto);
        return $this->sendSuccess(['user' => $user], message:"Registration Successful");
        // return response()->json(['user'=>$user, 'success' => true, 'message' => 'Registration Successful' ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        // dd($credentials);

        $user = User::where('email', $credentials['email'])->first();
        // dd($user);


        if(!Auth::attempt($credentials))
        {
            return $this->sendError(message: 'The provided credentials are incorrect.');
        }

        $user = $request->user();
        // dd($user);

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->sendSuccess([
            'user' => $user,
            'token' => $token]
            , message: 'Logged in Successfully',
            
        );
    }

    public function user(Request $request): JsonResponse
    {
        return $this->sendSuccess([
            'user' => $request->user(),
        ]
            , message: 'Authenticated User Retrieved Successfully',
            
        );
    }

    public function logout(Request $request): JsonResponse
    {

        $user = $request->user();
        $user->tokens()->delete();
        return $this->sendSuccess([], message: 'Logged Out Successfully',
            
        );
    }
}
