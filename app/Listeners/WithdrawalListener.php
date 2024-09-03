<?php

namespace App\Listeners;

use App\Events\WithdrawalEvent;
use App\Services\TransactionService;
use App\Enum\TransactionCategoryEnum;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawalListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private readonly TransactionService $transactionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WithdrawalEvent $event): void
    {
        // dd($event->transactionDto);

        if($event->transactionDto->getCategory() != TransactionCategoryEnum::WITHDRAWAL->value){
            return;
        }
        // dd($event->transactionDto);
        $this->transactionService->createTransaction($event->transactionDto);
        $account = $event->lockedAccount;
        $account->balance = $account->balance - $event->transactionDto->getAmount();
        $account->save();
        $account = $account->refresh();
        $this->transactionService->updateTransactionBalance($event->transactionDto->getReference(), $account->balance);

        if($event->transactionDto->getTransferId())
        {
            $this->transactionService->updateTransferId($event->transactionDto->getReference(), $event->transactionDto->getTransferId());
        }
    }
}
