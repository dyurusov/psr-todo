<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


interface RouteInterface
{
    public function isMatched(): bool;
    public function getHandler();
    public function getAttributes(): array;
}