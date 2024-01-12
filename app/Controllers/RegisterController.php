<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Doctrine\DBAL\Exception;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\RedirectResponse;
use Pmguru\Framework\Http\Response;

final class RegisterController extends AbstractController
{
    
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }
    
    public function form()
    : Response
    {
        return $this->render('register.html.twig');
    }
    
    /**
     * @throws Exception
     */
    public function register()
    {
        // 1. Создаем модель формы
        $form = new RegisterForm($this->userService);
        $form->setFields(
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation'),
            $this->request->input('name'),
        );
        
        // 2. Валидация введённых в форму данных
        // если есть ошибки валидации, добавить их в сессию и переправить обратно на форму
        if ($form->hasValidationErrors()) {
            foreach($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('errors', $error);
            }
            return new RedirectResponse('/register');
        }
        
        // 3. Добавление пользователя в БД при помощи метода save()
        $user = $form->save();
        
        // 4. Добавить сообщение об успешной регистрации
        $this->request->getSession()->setFlash('success', "Пользователь {$user->getEmail()} успешно зарегистрирован");
        
        // 5. Войти в систему под пользователем
        
        // 6. Перенаправить на нужную страницу
        return new RedirectResponse('/register');
    }
    
}