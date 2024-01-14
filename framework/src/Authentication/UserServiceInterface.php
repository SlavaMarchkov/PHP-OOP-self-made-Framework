<?php

declare(strict_types=1);

namespace Pmguru\Framework\Authentication;

interface UserServiceInterface
{
    
    public function findByEmail(string $email)
    : ?AuthUserInterface;
    
}