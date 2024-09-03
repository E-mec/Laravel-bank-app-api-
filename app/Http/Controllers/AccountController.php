<?php

namespace App\Http\Controllers;

use App\Dtos\UserDto;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function __construct(private readonly AccountService $accountService)
    {

    }

    public function store(Request $request)
    {
        $userDto = UserDto::fromModel($request->user());
        // dd($userDto->getPhoneNumber(), $userDto->getId());
        $account = $this->accountService->createAccountNumber($userDto);
        return $this->sendSuccess(['account' => $account], message : 'Account Number generated successfully');
    }

    
}
