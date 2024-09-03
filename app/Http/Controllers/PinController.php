<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class PinController extends Controller
{
    public function setupPin(Request $request, UserService $userService): JsonResponse
    {
        $this->validate($request, [
            'pin' =>['string', 'required', 'min:4', 'max:4']
        ]);
        /** @var User $user */

        $user = $request->user();

        $userService->setupPin($user, $request->input('pin'));

        return $this->sendSuccess([], 'Pin is set successfully!');
    }

    public function validatePin(Request $request, UserService $userService): JsonResponse
    {
        $this->validate($request, [
            'pin' => ['string', 'required']
        ]);
        /** @var User $user */

        $user = $request->user();

        $isValid = $userService->validatePin($user->id, $request->input('pin'));

        return $this->sendSuccess(['is_valid'=> $isValid], 'Pin Validated!');
    }
}
