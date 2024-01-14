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
        private readonly SessionAuthInterface $sessionAuth,
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
        $isAuth = $this->sessionAuth->authenticate(
            $this->request->input('email'),
            $this->request->input('password'),
        );
        
        if (!$isAuth) {
            $this->request->getSession()->setFlash('errors', 'Неверный email или пароль!');
            return new RedirectResponse('/login');
        }
        
        $this->request->getSession()->setFlash('success', 'Вход выполнен успешно!');
        return new RedirectResponse('/dashboard');
    }
    
}