<?php

declare(strict_types=1);

namespace App\Forms\User;

use App\Entities\User;
use App\Services\UserService;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;

final class RegisterForm
{
    private ?string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;
    
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }
    
    public function setFields(
        string $email,
        string $password,
        string $passwordConfirmation,
        string $name = null,
    )
    : void {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
    
    /**
     * @throws Exception
     */
    public function save()
    : User
    {
        $user = User::create(
            $this->email,
            password_hash($this->password, PASSWORD_DEFAULT),
            new DateTimeImmutable(),
            $this->name
        );
        
        return $this->userService->save($user);
    }
    
    public function getValidationErrors()
    : array
    {
        $errors = [];
        
        if (!empty($this->name) && strlen($this->name) > 32) {
            $errors[] = 'Максимальная длина имени должна быть 32 символа';
        }
        
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный адрес email или поле пустое';
        }
        
        if (empty($this->password) || strlen($this->password) < 8) {
            $errors[] = 'Минимальная длина пароля должна быть 8 символов';
        }
        
        if ($this->password !== $this->passwordConfirmation) {
            $errors[] = 'Пароли не совпадают';
        }
        
        return $errors;
    }
    
    public function hasValidationErrors()
    : bool
    {
        return !empty($this->getValidationErrors());
    }
    
}