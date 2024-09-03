<?php

namespace App\Interfaces;

use App\Dtos\UserDto;
use App\Models\Account;
use App\Dtos\AccountDto;
use App\Dtos\DepositDto;
use App\Dtos\WithdrawDto;
use App\Dtos\TransactionDto;
use App\Dtos\TransferDto;
use Illuminate\Database\Eloquent\Builder;

interface AccountServiceInterface
{
    public function modelQuery(): Builder;

    public function createAccountNumber(UserDto $userDto): Account;

    public function getAccountByAccountNumber(string $accountNumber): Account;

    public function getAccountByUserId(int $userId): Account;

    public function getAccount(int|string $accountNumberOrUserId): Account;

    public function deposit(DepositDto $depositDto): TransactionDto;

    public function withdraw(WithdrawDto $withdrawDto): TransactionDto;

    public function transfer(string $senderAccountNumber,string $receiverAccountNumber,string $senderAccountPin ,int|float $amount,string $description = null): TransferDto;

    public function canWithdraw(AccountDto $accountDto, WithdrawDto $withdrawDto): bool;

    public function accountExist(Builder $accountQuery): void;


}
