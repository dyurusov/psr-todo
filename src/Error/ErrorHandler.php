<?php

namespace App\Error;

use App\Template\TemplateTrait;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use App\Template\TemplateEngineInterface;


class ErrorHandler
{
    use TemplateTrait;

    public function __construct(TemplateEngineInterface $templateEngine)
    {
        $this->setTemplateEngine($templateEngine);
    }

    public function handle(\Exception $e): ResponseInterface
    {
        $response = (new Response())
            ->withStatus($e->getCode() ?: 500);
        $response->getBody()
            ->write($this->render('error', [ 'exception' => $e ]));
        return $response;
    }
}