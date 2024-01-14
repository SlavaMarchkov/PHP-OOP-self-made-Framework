<?php

declare(strict_types=1);

namespace App\Controllers;

use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Response;

final class DashboardController extends AbstractController
{
    
    public function index()
    : Response
    {
        return $this->render('dashboard.html.twig');
    }
    
}