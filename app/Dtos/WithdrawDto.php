<?php

namespace App\Dtos;

use App\Enum\TransactionCategoryEnum;

class WithdrawDto
{
    private string $account_number; //withdrawal's account number
    private int|float $amount;
    private string|null $description;
    private string $pin;
    private string $category;




	public function getAccount_number(): string
    {
		return $this->account_number;
	}

	public function setAccount_number($account_number) {
		$this->account_number = $account_number;
	}

	public function getAmount() : int|float
    {
		return $this->amount;
	}

	public function setAmount(int|float $amount)
    {
		$this->amount = $amount;
	}

	public function getDescription() : string|null
    {
		return $this->description;
	}

	public function setDescription(string|null $description)
    {
		$this->description = $description;
	}

	public function getPin() : string
    {
		return $this->pin;
	}

	public function setPin(string $pin)
    {
		$this->pin = $pin;
	}

	public function getCategory() : string
    {
        $this->setCategory(TransactionCategoryEnum::WITHDRAWAL->value);

		return $this->category;
	}

	public function setCategory(string $category)
    {
		$this->category = $category;
	}
}
