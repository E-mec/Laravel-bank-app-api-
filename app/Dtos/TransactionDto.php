<?php

namespace App\Dtos;

use App\Enum\TransactionCategoryEnum;
use Carbon\Carbon;

class TransactionDto
{
    private int|null $id;
    private string $reference;
    private int $user_id;
    private int $account_id;
    private int|null $transfer_id;
    private float $amount;
    private float $balance;
    private string $category;
    private string|null $description;
    private string|null $meta;
    private Carbon $date;
    private bool $confirmed;
    private Carbon $created_at;
    private Carbon $updated_at;


    /**
     * Get the transaction ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the transaction ID
     *
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the transaction reference
     *
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Set the transaction reference
     *
     * @param string $reference
     * @return self
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Get the user ID
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Set the user ID
     *
     * @param int $user_id
     * @return self
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get the account ID
     *
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->account_id;
    }

    /**
     * Set the account ID
     *
     * @param int $account_id
     * @return self
     */
    public function setAccountId(int $account_id): self
    {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * Get the transfer ID
     *
     * @return int|null
     */
    public function getTransferId(): ?int
    {
        return $this->transfer_id;
    }

    /**
     * Set the transfer ID
     *
     * @param int|null $transfer_id
     * @return self
     */
    public function setTransferId(?int $transfer_id): self
    {
        $this->transfer_id = $transfer_id;
        return $this;
    }

    /**
     * Get the transaction amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the transaction amount
     *
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get the transaction balance
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the transaction balance
     *
     * @param float $balance
     * @return self
     */
    public function setBalance(float $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Get the transaction category
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set the transaction category
     *
     * @param string $category
     * @return self
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get the transaction description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the transaction description
     *
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the transaction meta
     *
     * @return string|null
     */
    public function getMeta(): ?string
    {
        return $this->meta;
    }

    /**
     * Set the transaction meta
     *
     * @param string|null $meta
     * @return self
     */
    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get the transaction date
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Set the transaction date
     *
     * @param Carbon $date
     * @return self
     */
    public function setDate(Carbon $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the transaction confirmed status
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * Set the transaction confirmed status
     *
     * @param bool $confirmed
     * @return self
     */
    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * Get the transaction created at date
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * Set the transaction created at date
     *
     * @param Carbon $created_at
     * @return self
     */

    public function setCreatedAt(Carbon $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }


    /**
     * Get the transaction updated at date
     *
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * Set the transaction updated at date
     *
     * @param Carbon $updated_at
     * @return self
     */
    public function setUpdatedAt(Carbon $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function forDeposit(AccountDto $accountDto, string $reference, float|int $amount, string|null $description): self
    {
        $dto = new TransactionDto();
        $dto->setUserId($accountDto->getUserId())
            ->setReference($reference)
            ->setAccountId($accountDto->getId())
            ->setAmount($amount)
            ->setTransferId(null)
            ->setCategory(TransactionCategoryEnum::DEPOSIT->value)
            ->setDate(Carbon::now())
            ->setDescription($description);

        return $dto;

    }

    public function forWithdrawal(AccountDto $accountDto, string $reference, WithdrawDto $withdrawDto): self
    {
        $dto = new TransactionDto();
        $dto->setUserId($accountDto->getUserId())
            ->setReference($reference)
            ->setAccountId($accountDto->getId())
            ->setAmount($withdrawDto->getAmount())
            ->setTransferId(null)
            // ->setCategory(TransactionCategoryEnum::WITHDRAWAL->value)
            ->setCategory($withdrawDto->getCategory())
            ->setDate(Carbon::now())
            ->setDescription($withdrawDto->getDescription());

        return $dto;

    }

    public function forDepositToArray(TransactionDto $transactionDto): array
    {

        return [
            'amount' => $transactionDto->getAmount(),
            'user_id' => $transactionDto->getUserId(),
            'reference' => $transactionDto->getReference(),
            'account_id' => $transactionDto->getAccountId(),
            'category' => $transactionDto->getCategory(),
            'date' => $transactionDto->getDate(),
            'description' => $transactionDto->getDescription(),

        ];

    }

    public function forWithdrawalToArray(TransactionDto $transactionDto): array
    {

        return [
            'amount' => $transactionDto->getAmount(),
            'user_id' => $transactionDto->getUserId(),
            'reference' => $transactionDto->getReference(),
            'account_id' => $transactionDto->getAccountId(),
            'category' => $transactionDto->getCategory(),
            'date' => $transactionDto->getDate(),
            'description' => $transactionDto->getDescription(),

        ];

    }
}
