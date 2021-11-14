<?php

namespace App\Router;

use Psr\Http\Server\RequestHandlerInterface;
use Aura\Router\Route as AuraRouter;


class Route implements RouteInterface
{
    private $auraRouter;

    public function __construct(AuraRouter $auraRouter = null, RequestHandlerInterface $handler = null)
    {
        $this->auraRouter = $auraRouter ?: new AuraRouter();
        if ($handler) {
            $this->auraRouter->handler($handler);
        }
    }

    public function __get($key)
    {
        return $this->auraRouter->$key;
    }

    public function isMatched(): bool
    {
        return !!$this->auraRouter->handler;
    }

    public function getHandler()
    {
        return $this->auraRouter->handler ;
    }

    public function getAttributes(): array
    {
        return $this->auraRouter->attributes ;
    }
}