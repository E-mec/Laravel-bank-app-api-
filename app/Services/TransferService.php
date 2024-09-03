<?php

namespace App\Services;

use Carbon\Carbon;
use App\Dtos\AccountDto;
use App\Models\Transfer;
use App\Dtos\TransferDto;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\TransferServiceInterface;

class TransferService implements TransferServiceInterface
{

    public function modelQuery(): Builder
    {
        return Transfer::query();
    }

    public function createTransfer(TransferDto $transferDto): Transfer
    {
        /**
         * @var Transfer $transfer
         */
        $transfer = $this->modelQuery()->create([
            'sender_id' => $transferDto->getSender_id(),
            'recipient_id' => $transferDto->getRecipient_id(),

            'sender_account_id' => $transferDto->getSender_account_id(),
            'recipient_account_id' => $transferDto->getRecipient_account_id(),

            'reference' => $transferDto->getReference(),

            'status' => $transferDto->getStatus(),

            'amount' => $transferDto->getAmount()

        ]);

        return $transfer;
    }

    public function getTransferBetweenAccount(AccountDto $firstAccountDto, AccountDto $secondAccountDto): array
    {

    }

    public function generateReference(): string
    {
        return Str::upper('TRF'. '/' . Carbon::now()->timestamp . '/'. Str::random(4));
    }

    public function getTransferById(int $transferId): Transfer
    {
        /**
         * @var Transfer $transfer
         */
        $transfer = $this->modelQuery()->where('id', $transferId)->first();
        if (!$transfer) {
            throw new \Exception('Transfer not found');
            }
            return $transfer;
    }

    public function getTransferByReference(string $reference): Transfer
    {
        /**
         * @var Transfer $transfer
         */
        $transfer = $this->modelQuery()->where('reference', $reference)->first();
        if (!$transfer) {
            throw new \Exception('Transfer with supplied reference not found');
            }
            return $transfer;
    }

}
