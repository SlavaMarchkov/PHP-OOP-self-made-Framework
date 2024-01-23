<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Events;

use Pmguru\Framework\Event\Event;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

final class ResponseEvent extends Event
{
    
    public function __construct(
        private readonly Request $request,
        private readonly Response $response,
    ) {
    }
    
    public function getRequest()
    : Request
    {
        return $this->request;
    }
    
    public function getResponse()
    : Response
    {
        return $this->response;
    }
    
}