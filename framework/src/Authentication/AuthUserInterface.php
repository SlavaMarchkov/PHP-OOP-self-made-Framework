<?php

declare(strict_types=1);

namespace Pmguru\Framework\Authentication;

interface AuthUserInterface
{
    
    public function getId()
    : int;
    
    public function getEmail()
    : string;
    
    public function getPassword()
    : string;
    
}