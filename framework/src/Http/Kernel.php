<?php

namespace Pmguru\Framework\Http;

use Exception;
use League\Container\Container;
use Pmguru\Framework\Event\EventDispatcher;
use Pmguru\Framework\Http\Events\ResponseEvent;
use Pmguru\Framework\Http\Exceptions\HttpException;
use Pmguru\Framework\Http\Middleware\RequestHandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Kernel
{
    
    private string $appEnv;
    
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly Container $container,
        private readonly RequestHandlerInterface $requestHandler,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        $this->appEnv = $this->container->get('APP_ENV');
    }
    
    /**
     * @throws Exception
     */
    public function handle(Request $request)
    : Response {
        try {
            // проверка выброса исключения
            // throw new Exception('Some fatal error');
            
            // проверка подключения к БД
            // dd($this->container->get(Connection::class));
            // dd($this->container->get(Connection::class)->connect());
            
            $response = $this->requestHandler->handle($request);
        } catch (Exception $e) {
            $response = $this->createExceptionResponse($e);
        }
        
        // $response->setStatusCode(500);
        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));
        
        return $response;
    }
    
    public function terminate(Request $request, Response $response)
    : void {
        $request->getSession()?->clearFlash();
    }
    
    /**
     * @throws Exception
     */
    private function createExceptionResponse(Exception $e)
    : Response {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }
        
        return new Response('Server error', 500);
    }
    
}