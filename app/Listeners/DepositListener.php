<?php

namespace App\Listeners;

use App\Enum\TransactionCategoryEnum;
use App\Events\DepositEvent;
use App\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DepositListener
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
    public function handle(DepositEvent $event): void
    {
        if($event->transactionDto->getCategory() != TransactionCategoryEnum::DEPOSIT->value){
            return;
        }
        // dd($event->transactionDto);
        $this->transactionService->createTransaction($event->transactionDto);
        $account = $event->lockedAccount;
        $account->balance = $account->balance + $event->transactionDto->getAmount();
        $account->save();
        $account = $account->refresh();
        $this->transactionService->updateTransactionBalance($event->transactionDto->getReference(), $account->balance);

        if($event->transactionDto->getTransferId())
        {
            $this->transactionService->updateTransferId($event->transactionDto->getReference(), $event->transactionDto->getTransferId());
        }

    }
}
