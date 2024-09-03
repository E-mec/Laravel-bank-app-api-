<?php

namespace App\Http\Controllers;

use App\Dtos\DepositDto;
use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use App\Services\AccountService;

class AccountDepositController extends Controller
{
    public function __construct(private readonly AccountService $accountService)
    {

    }

    public function store(DepositRequest $request)
    {
        $depositDto = new DepositDto();
        $depositDto->setAccount_number($request->input("account_number"));
        $depositDto->setAmount($request->input("amount"));
        $depositDto->setDescription($request->input("description"));

        // $depositDto->setCategory();
        $this->accountService->deposit($depositDto);
        return $this->sendSuccess([], 'Deposit Successful');
    }
}
