<?php

namespace App\Actions;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new \Laminas\Diactoros\Response();
        $response->getBody()->write("IndexAction");
        return $response;
    }
}