<?php

namespace App\Interfaces;

use App\Models\User;
use App\Dtos\UserDto;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface
{
    public function createUser(UserDto $userDto): Model;

    public function getUserById(int $userId): Model;
    
    public function setupPin(User $user, string $pin): void;

    public function validatePin(int $userId, string $pin): bool;

    public function hasSetPin(User $user): bool;



}