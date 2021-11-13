<?php

namespace App;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Error\Exceptions\NotFoundException;
use App\Error\ErrorHandler;


class QueueRequestHandler implements RequestHandlerInterface
{
    private array $middleware = [];
    private ErrorHandler $errorHandler;

    public function __construct(ErrorHandler $errorHandler) {
        $this->errorHandler = $errorHandler;
    }

    public function add(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (0 === count($this->middleware)) {
                throw new NotFoundException();
            }

            $middleware = array_shift($this->middleware);
            return $middleware->process($request, $this);
        } catch (\Exception $e) {
            return $this->errorHandler->handle($e);
        }
    }
}
