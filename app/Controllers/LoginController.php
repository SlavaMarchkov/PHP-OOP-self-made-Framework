<?php

declare(strict_types=1);

namespace App\Controllers;

use Pmguru\Framework\Authentication\SessionAuthInterface;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\RedirectResponse;
use Pmguru\Framework\Http\Response;

final class LoginController extends AbstractController
{
    
    public function __construct(
        private readonly SessionAuthInterface $auth,
    )
    {
    }
    
    public function form()
    : Response
    {
        return $this->render('login.html.twig');
    }
    
    public function login()
    : RedirectResponse
    {
        $isAuth = $this->auth->authenticate(
            $this->request->input('email'),
            $this->request->input('password'),
        );
        
        if (!$isAuth) {
            $this->request->getSession()->setFlash('errors', 'Неверный email или пароль.');
            return new RedirectResponse('/login');
        }
        
        $this->request->getSession()->setFlash('success', 'Вход выполнен успешно.');
        return new RedirectResponse('/dashboard');
    }
    
    public function logout()
    : RedirectResponse
    {
        $this->auth->logout();
        $this->request->getSession()->setFlash('success', "Вы вышли из аккаунта.");
        return new RedirectResponse('/login');
    }
    
}