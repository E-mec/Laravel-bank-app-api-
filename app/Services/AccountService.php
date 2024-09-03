<?php

namespace App\Services;

use Exception;
use App\Dtos\UserDto;
use App\Models\Account;
use App\Dtos\AccountDto;
use App\Dtos\DepositDto;
use App\Dtos\WithdrawDto;
use App\Dtos\TransactionDto;
use App\Dtos\TransferDto;
use App\Events\DepositEvent;
use App\Events\WithdrawalEvent;
use App\Events\TransactionEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\AccountServiceInterface;
// use Illuminate\Foundation\Exceptions\Renderer\Exception;


class AccountService implements AccountServiceInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly TransactionService $transactionService,
        private readonly TransferService $transferService
    ) {}

    public function modelQuery(): Builder
    {
        return Account::query();
    }

    public function hasAccountNumber(UserDto $userDto): bool
    {
        return $this->modelQuery()->where('user_id', $userDto->getId())->exists();
    }

    public function createAccountNumber(UserDto $userDto): Account
    {
        if ($this->hasAccountNumber($userDto)) {
            throw new Exception("You already have an account number", 1);
        }

        return $this->modelQuery()->create([
            'account_number' => substr($userDto->getPhoneNumber(), -10),
            'user_id' => $userDto->getId(),
        ]);
    }



    public function getAccountByAccountNumber(string $accountNumber): Account {}

    public function getAccountByUserId(int $userId): Account
    {
        $account = $this->modelQuery()->where('user_id', $userId)->first();

        if (!$account) {
            throw new Exception("Account not found", 1);
        }
        return $account;
    }

    public function getAccount(int|string $accountNumberOrUserId): Account {}

    public function deposit(DepositDto $depositDto): TransactionDto
    {
        // dd($depositDto);
        $minimum_deposit = 500;

        if ($depositDto->getAmount() < $minimum_deposit) {
            throw new Exception('Deposit Amount must be greater than ' . $minimum_deposit);
        }

        try {
            DB::beginTransaction();
            $transactionDto = new TransactionDto();
            $accountQuery = $this->modelQuery()->where('account_number', $depositDto->getAccount_number());
            $this->accountExist($accountQuery);
            /**
             * @var Account $lockedAccount
             */
            $lockedAccount = $accountQuery->lockForUpdate()->first();
            $accountDto = AccountDto::fromModel($lockedAccount);
            // dd($accountDto);

            $transactionDto = $transactionDto->forDeposit(
                $accountDto,
                $this->transactionService->generateReference(),
                $depositDto->getAmount(),
                $depositDto->getDescription()
            );
            // dd($accountDto);
            event(new DepositEvent($transactionDto, $accountDto, $lockedAccount));
            DB::commit();
            return $transactionDto;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function accountExist(Builder $accountQuery): void
    {
        // $accountQuery->firstOrFail();

        if ($accountQuery->exists() == false) {
            throw new Exception('Invalid Account Number');
        }
    }

    public function withdraw(WithdrawDto $withdrawDto): TransactionDto
    {

        $minimum_withdrawal = 500;

        if ($withdrawDto->getAmount() < $minimum_withdrawal) {
            throw new Exception('Withdrawal Amount must be greater than or equal to ' . $minimum_withdrawal);
        }

        try {
            DB::beginTransaction();
            // $transactionDto = new TransactionDto();
            $accountQuery = $this->modelQuery()->where('account_number', $withdrawDto->getAccount_number());
            $this->accountExist($accountQuery);
            /**
             * @var Account $lockedAccount
             */
            $lockedAccount = $accountQuery->lockForUpdate()->first();
            $accountDto = AccountDto::fromModel($lockedAccount);
            // dd( $withdrawDto->getPin());
            if (!$this->userService->validatePin($accountDto->getUserId(), $withdrawDto->getPin())) {
                throw new Exception("Pin is Invalid");
            }
            $this->canWithdraw($accountDto, $withdrawDto);
            $transactionDto = new TransactionDto();
            $transactionDtoForWithdrawal = $transactionDto->forWithdrawal($accountDto, $this->transactionService->generateReference(), $withdrawDto);
            // dd($transactionDtoForWithdrawal);
            event(new WithdrawalEvent($transactionDtoForWithdrawal, $accountDto, $lockedAccount));
            // dd($transactionDtoForWithdrawal);

            DB::commit();
            return $transactionDto;
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }
    }

    public function transfer(string $senderAccountNumber, string $receiverAccountNumber, string $senderAccountPin, int|float $amount, string $description = null): TransferDto
    {
        if ($senderAccountNumber == $receiverAccountNumber) {
            throw new Exception('Receiver and sender account number cannot be the same');
        }
        $minimum_transfer = 300;

        // if ($withdrawDto->getAmount() < $minimum_withdrawal) {
        //     throw new Exception('Transfer Amount must be greater than or equal to ' . $minimum_withdrawal);
        // }

        try {
            DB::beginTransaction();

            $senderAccountQuery = $this->modelQuery()->where('account_number', $senderAccountNumber);
            $receiverAccountQuery = $this->modelQuery()->where('account_number', $receiverAccountNumber);

            $this->accountExist($senderAccountQuery);
            $this->accountExist($receiverAccountQuery);
            /**
             * @var Account $lockedSenderAccount
             */
            $lockedSenderAccount = $senderAccountQuery->lockForUpdate()->first();
            /**
             * @var Account $lockedReceiverAccount
             */
            $lockedReceiverAccount = $receiverAccountQuery->lockForUpdate()->first();
            $lockedSenderAccountDto = AccountDto::fromModel($lockedSenderAccount);
            $lockedReceiverAccountDto = AccountDto::fromModel($lockedReceiverAccount);


            if (!$this->userService->validatePin($lockedSenderAccountDto->getUserId(), $senderAccountPin)) {
                throw new Exception("Pin is Invalid");
            }

            $transactionDto = new TransactionDto();
            $withdrawDto = new WithdrawDto();
            $depositDto = new DepositDto();
            $transferDto = new TransferDto();

            $withdrawDto->setAccount_number($lockedSenderAccountDto->getAccountNumber());
            $withdrawDto->setAmount($amount);
            $withdrawDto->setDescription($description);
            $withdrawDto->setPin($senderAccountPin);

            $depositDto->setAccount_number($lockedReceiverAccountDto->getAccountNumber());
            $depositDto->setAmount($amount);
            $depositDto->setDescription($description);

            $this->canWithdraw($lockedSenderAccountDto, $withdrawDto);

            $transactionWithdrawalDto = $transactionDto->forWithdrawal(
                $lockedSenderAccountDto,
                $this->transactionService->generateReference(),
                $withdrawDto
            );

            $transactionDepositDto = $transactionDto->forDeposit(
                $lockedReceiverAccountDto,
                $this->transactionService->generateReference(),
                $depositDto->getAmount(),
                $depositDto->getDescription()
            );

            $transferDto->setReference($this->transferService->generateReference());

            $transferDto->setSender_id($lockedSenderAccountDto->getUserId());
            $transferDto->setSender_account_id($lockedSenderAccountDto->getId());

            $transferDto->setRecipient_id($lockedReceiverAccountDto->getUserId());
            $transferDto->setRecipient_account_id($lockedReceiverAccountDto->getId());

            $transferDto->setAmount($amount);

            $transferDto->setStatus('success');

            $transfer = $this->transferService->createTransfer($transferDto);

            $transactionWithdrawalDto->setTransferId($transfer->id);
            $transactionDepositDto->setTransferId($transfer->id);

            event(new WithdrawalEvent($transactionWithdrawalDto, $lockedSenderAccountDto, $lockedSenderAccount));

            event(new DepositEvent($transactionDepositDto, $lockedReceiverAccountDto, $lockedReceiverAccount));

            // dd($transactionDtoForWithdrawal);



            DB::commit();
            return $transferDto;
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }
    }

    public function canWithdraw(AccountDto $accountDto, WithdrawDto $withdrawDto): bool
    {
        // If the account is blocked

        // if the user has not exceeded their transaction limit for the day or month

        // if the amount to withdraw is not greater than the user balance
        if ($accountDto->getBalance() < $withdrawDto->getAmount()) {
            throw new Exception('Insufficient Balance');
        }

        // if the amount left after withdrawal is not more than the minimum account balance

        return true;
    }
}
