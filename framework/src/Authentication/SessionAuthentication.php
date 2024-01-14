<?php

declare(strict_types=1);

namespace Pmguru\Framework\Authentication;

use Pmguru\Framework\Session\SessionInterface;

final class SessionAuthentication implements SessionAuthInterface
{
    
    private AuthUserInterface $user;
    
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly SessionInterface $session,
    )
    {
    }
    
    public function authenticate(string $email, string $password)
    : bool {
        $user = $this->userService->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user->getPassword())) {
            $this->login($user);
            return true;
        }
        
        return false;
    }
    
    public function login(AuthUserInterface $user)
    : void {
        $this->session->set('user_id', $user->getId());
        $this->user = $user;
    }
    
    public function logout()
    {
        // TODO: Implement logout() method.
    }
    
    public function getUser()
    : AuthUserInterface
    {
        return $this->user;
    }
    
}