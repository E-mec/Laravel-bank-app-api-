<?php

namespace App\Services;

use App\Dtos\UserDto;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService implements UserServiceInterface
{
    public function createUser(UserDto $userDto): Model
    {
        return User::query()->create([
            'name' => $userDto->getName(),
            'email' => $userDto->getEmail(),
            'password' => $userDto->getPassword(),
            'phone_number'=>$userDto->getPhoneNumber()
        ]);
    }

    public function getUserById(int $userId): Model
    {
        $user = User::query()->where('id', $userId)->first();

        if (!$user) {
            throw new ModelNotFoundException('User Not Found');
        }
        return $user;
    }

    public function setupPin(User $user, string $pin): void
    {
        if($this->hasSetPin($user)) {
            throw new BadRequestException('Pin Has already been set');
        }

        if(strlen($pin) != 4) {
            throw new Exception('Length of pin must be equal to 4');
        }

        $user->pin = Hash::make($pin);
        $user->save();
    }

    public function validatePin(int $userId, string $pin): bool
    {
        $user = $this->getUserById($userId);

        if(!$this->hasSetPin($user)) {
            throw new BadRequestException('Please Set your pin');
        }

        return Hash::check($pin, $user->pin);

    } 

    public function hasSetPin(User $user): bool
    {
        return $user->pin != null ;
    }

    
}


