<?php

namespace App\Actions;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReadAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $response = new \Laminas\Diactoros\Response();
        $response->getBody()->write("ReadAction $id");
        return $response;
    }
}