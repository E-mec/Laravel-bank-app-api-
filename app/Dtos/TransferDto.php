<?php

namespace App\Dtos;

use Carbon\Carbon;

class TransferDto
{
    private ?int $id;
    private int $sender_id;
    private int $sender_account_id;
    private int $recipient_id;
    private int $recipient_account_id;
    private float|int $amount;
    private string $status;
    private string $reference;
    private Carbon $created_at;
    private Carbon $updated_at;



	public function getId() : ?int {
		return $this->id;
	}

	public function setId(?int $value) {
		$this->id = $value;
	}

	public function getSender_id() : int {
		return $this->sender_id;
	}

	public function setSender_id(int $value) {
		$this->sender_id = $value;
	}

	public function getSender_account_id() : int {
		return $this->sender_account_id;
	}

	public function setSender_account_id(int $value) {
		$this->sender_account_id = $value;
	}

	public function getRecipient_id() : int {
		return $this->recipient_id;
	}

	public function setRecipient_id(int $value) {
		$this->recipient_id = $value;
	}

	public function getRecipient_account_id() : int {
		return $this->recipient_account_id;
	}

	public function setRecipient_account_id(int $value) {
		$this->recipient_account_id = $value;
	}

	public function getAmount() : float|int {
		return $this->amount;
	}

	public function setAmount(float|int $value) {
		$this->amount = $value;
	}

	public function getStatus() : string {
		return $this->status;
	}

	public function setStatus(string $value) {
		$this->status = $value;
	}

	public function getReference() : string {
		return $this->reference;
	}

	public function setReference(string $value) {
		$this->reference = $value;
	}

	public function getCreated_at() : Carbon {
		return $this->created_at;
	}

	public function setCreated_at(Carbon $value) {
		$this->created_at = $value;
	}

	public function getUpdated_at() : Carbon {
		return $this->updated_at;
	}

	public function setUpdated_at(Carbon $value) {
		$this->updated_at = $value;
	}
}
