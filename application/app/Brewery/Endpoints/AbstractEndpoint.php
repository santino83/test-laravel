<?php

namespace App\Brewery\Endpoints;

use App\Brewery\BreweryContext;
use App\Brewery\Mapper\Mapper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

abstract class AbstractEndpoint
{

    protected const string POST = 'post';
    protected const string PUT = 'put';
    protected const string DELETE = 'delete';
    protected const string GET = 'get';
    protected const string PATCH = 'patch';
    protected const string HEAD = 'head';

    public function __construct(protected BreweryContext $context)
    {
    }

    protected function getContext(): BreweryContext
    {
        return $this->context;
    }

    protected function getClient(): Client
    {
        return $this->context->getClient();
    }

    protected function getMapper(): Mapper
    {
        return $this->context->getMapper();
    }

    /**
     * @param string $method
     * @param string|Uri $uri
     * @param string|null $body
     * @param array $headers
     * @return string|int
     * @throws \Throwable on error
     */
    protected function send(string $method, string|Uri $uri, ?string $body = null, array $headers = []): string|int
    {
        $this->doLog(sprintf("%s::Sending request to %s via %s", get_class($this), $uri, $method));

        $request = new Request($method, $uri, $headers, $body);

        try {

            $response = $this->getClient()->send($request);

        } catch (\Throwable $ex) {

            $this->doLog($ex, 'error');
            throw $ex;
        }

        $body = $response->getBody();

        return !$body->isSeekable() ? $response->getStatusCode() : ($body->__toString() ?? $response->getStatusCode());
    }

    protected function doLog(string|\Throwable $message, string $level = 'info', array $context = []): void
    {
        $logger = $this->getContext()?->getLogger();
        if (!$logger) return;

        if (is_string($message)) {
            $logger->$level($message, $context);
        } elseif (is_a($message, \Throwable::class)) {
            $logger->$level($message->getMessage());
        }
    }

}
