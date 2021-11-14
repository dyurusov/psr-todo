<?php

namespace App\Error;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use App\Template\TemplateEngineInterface;


class ErrorHandler
{
    private $templateEngine;

    public function __construct(TemplateEngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    public function handle(\Exception $e): ResponseInterface
    {
        $response = (new Response())
            ->withStatus($e->getCode() ?: 500);
        $response->getBody()
            ->write($this->templateEngine->render('error', [ 'exception' => $e ]));
        return $response;
    }
}