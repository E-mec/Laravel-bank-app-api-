<?php

namespace App\Dtos;

use Carbon\Carbon;
use App\Models\User;
use App\Interfaces\DtoInterface;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserDto implements DtoInterface
{

    private ?int $id;

    private string $email;
    private string $name;

    private string $phone_number;

    private ?string $pin;

    private string $password;

    private ?Carbon $created_at;

    private ?Carbon $updated_at;

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    // Setters
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    public function setPin(?string $pin): self
    {
        $this->pin = $pin;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setCreatedAt(?Carbon $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt(?Carbon $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public static function fromApiFormRequest(FormRequest $request): DtoInterface
    {
        $userDto = new UserDto();
        $userDto->setName($request->input('name'));
        $userDto->setEmail($request->input('email'));
        $userDto->setPassword($request->input('password'));
        $userDto->setPhoneNumber($request->input('phone_number'));
        return $userDto;

    }

    public static function fromModel(User|Model $model): UserDto
    {
        $userDto = new UserDto();
        $userDto->setId($model->id);
        $userDto->setName($model->name);
        $userDto->setEmail($model->email);
        $userDto->setPhoneNumber($model->phone_number);
        $userDto->setCreatedAt($model->created_at);
        $userDto->setUpdatedAt($model->updated_at);
        return $userDto;
    }

    public static function toArray(Model|User $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'phone_number' => $model->phone_number,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }
}
