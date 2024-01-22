<?php

declare(strict_types=1);

namespace Pmguru\Framework\Authentication;

use Pmguru\Framework\Session\Session;
use Pmguru\Framework\Session\SessionInterface;

class SessionAuthentication implements SessionAuthInterface
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
        $this->session->set(Session::AUTH_KEY, $user->getId());
        $this->user = $user;
    }
    
    public function logout()
    : void
    {
        $this->session->remove(Session::AUTH_KEY);
    }
    
    public function getUser()
    : AuthUserInterface
    {
        return $this->user;
    }
    
    /**
     * @return bool
     */
    public function check()
    : bool
    {
        return $this->session->has(Session::AUTH_KEY);
    }
    
}