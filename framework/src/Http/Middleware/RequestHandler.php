<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use JetBrains\PhpStorm\NoReturn;
use Pmguru\Framework\Http\Exceptions\NotFoundException;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class RequestHandler implements RequestHandlerInterface
{
    
    private array $middleware = [
        ExtractRouteInfo::class,
        StartSession::class,
        RouterDispatch::class,
    ];
    
    public function __construct(
        private readonly ContainerInterface $container
    )
    {
    }
    
    /**
     * @throws NotFoundException
     */
    public function handle(Request $request)
    : Response {
        // dd($this->container);
        
        // Если нет middleware-классов, то вернуть ответ по умолчанию
        // Ответ должен быть возвращен до того, как список станет пустым
        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }
        
        // Получить следующий middleware-класс для выполнения
        $middlewareClass = array_shift($this->middleware);
        
        // Создать новый экземпляр этого класса и вызывать у него метод process
        try {
            $middleware = $this->container->get($middlewareClass);
            return $middleware->process($request, $this);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            throw new NotFoundException($e->getMessage(), 500);
        }
    }
    
    /**
     * @param array $middleware
     * @return void
     */
    #[NoReturn] public function injectMiddleware(array $middleware)
    : void {
        array_splice($this->middleware, 0, 0, $middleware);
    }
    
}