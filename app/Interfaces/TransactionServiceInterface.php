<?php

namespace App\Interfaces;

use Carbon\Carbon;
use App\Models\Account;
use App\Dtos\AccountDto;
use App\Models\Transaction;
use App\Dtos\TransactionDto;
// use Illuminate\Support\Collection;
// use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface TransactionServiceInterface
{
    public function modelQuery(): Builder;

    public function createTransaction(TransactionDto $transactionDto): Transaction;

    public function generateReference(): string;

    public function getTransactionByReference(string $reference): Transaction;

    public function getTransactionById(int $transactionID): Transaction;

    public function getTransactionByAccountNumber(int $accountNumber, Builder $builder): Builder;

    public function getTransactionByUserId(int $userID, Builder $builder): Builder;

    public function downloadTransactionHistory(AccountDto $accountDto, Carbon $fromDate, Carbon $endDate): Collection;





}
