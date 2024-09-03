<?php

namespace App\Http\Controllers;

use App\Dtos\WithdrawDto;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Http\Requests\WithdrawRequest;

class AccountWithdrawalController extends Controller
{
    public function __construct(private readonly AccountService $accountService)
    {

    }

    public function store(WithdrawRequest $request)
    {
        $account = $this->accountService->getAccountByUserId($request->user()->id);
        $withdrawDto = new WithdrawDto();
        $withdrawDto->setAccount_number($account->account_number);
        $withdrawDto->setAmount($request->input("amount"));
        $withdrawDto->setDescription($request->input("description"));
        $withdrawDto->setPin($request->input("pin"));

        // $withdrawDto->setCategory();
        $this->accountService->withdraw($withdrawDto);
        return $this->sendSuccess([], 'Withdraw Successful');
    }
}
