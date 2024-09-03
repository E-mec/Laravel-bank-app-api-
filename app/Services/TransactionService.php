<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Account;
use App\Dtos\AccountDto;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Dtos\TransactionDto;
use App\Enum\TransactionCategoryEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\TransactionServiceInterface;

/**
 * TransactionService
 */
class TransactionService implements TransactionServiceInterface
{
    public function modelQuery(): Builder
    {
        return Transaction::query();
    }

    public function generateReference(): string
    {
        return Str::upper('TF'. '/' . Carbon::now()->timestamp. '/'. Str::random(4) );
    }

    public function createTransaction(TransactionDto $transactionDto): Transaction
    {
        $data =  [];
        if($transactionDto->getCategory() == TransactionCategoryEnum::DEPOSIT->value)
        {
            $data = $transactionDto->forDepositToArray($transactionDto);
        }

        if($transactionDto->getCategory() == TransactionCategoryEnum::WITHDRAWAL->value)
        {
            $data = $transactionDto->forWithdrawalToArray($transactionDto);
        }
        /** @var Transaction $transaction */
        $transaction = $this->modelQuery()->create($data);
        return $transaction;
    }

    public function getTransactionByReference(string $reference): Transaction
    {
        $transaction = $this->modelQuery()->where('reference', '$reference')->first();

        if(!$transaction)
        {
            throw new \Exception('Transaction with the supplied reference does not exist');
        }
        return $transaction;
    }

    public function getTransactionById(int $transactionID): Transaction
    {
        $transaction = $this->modelQuery()->where('id', '$transactionID')->first();

        if(!$transaction)
        {
            throw new \Exception('Transaction with the supplied ID does not exist');
        }
        return $transaction;
    }

    public function getTransactionByAccountNumber(int $accountNumber, Builder $builder): Builder
    {
        return $builder->whereHas('account', function($query) use($accountNumber){
            $query->where('account_number', $accountNumber);
        });
    }

    public function getTransactionByUserId(int $userID, Builder $builder): Builder
    {
        return $builder->where('user_id', $userID);

    }

    public function downloadTransactionHistory(AccountDto $accountDto, Carbon $fromDate, Carbon $endDate): Collection
    {

    }

    public function updateTransactionBalance(string $reference, float|int $balance)
    {
        $this->modelQuery()->where('reference', $reference)->update([
            'balance' => $balance,
            'confirmed' => true
        ]);
    }

    public function updateTransferId(string $reference, int $transferId)
    {
        $this->modelQuery()->where('reference', $reference)->update([
            'transfer_id' => $transferId,

        ]);
    }

}
