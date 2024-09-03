<?php

namespace App\Dtos;
use App\Enum\TransactionCategoryEnum;

// use App\Dtos\DepositDto;

class DepositDto
{
    private string $account_number; //Receiver's account number
    private int|float $amount;
    private string|null $description;
    private string $category;


	public function getAccount_number() {
		return $this->account_number;
	}

	public function setAccount_number($value) {
		$this->account_number = $value;
	}

	public function getAmount() : int|float {
		return $this->amount;
	}

	public function setAmount(int|float $value) {
		$this->amount = $value;
	}

	public function getDescription() : string|null {
		return $this->description;
	}

	public function setDescription(string|null $value) {
		$this->description = $value;
	}

    public function getCategory() : string
    {
        $this->setCategory(TransactionCategoryEnum::DEPOSIT->value);

		return $this->category;
	}

	public function setCategory(string $category)
    {
		$this->category = $category;
	}

        // public function setCategory(string $value) {
        // 	$this->category = TransactionCategoryEnum::DEPOSIT->value;
        // }
} // End of DepositDto class

