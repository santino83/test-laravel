<?php

namespace App\Brewery;

use App\Brewery\Mapper\Mapper;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Utils;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;

class BreweryContext
{

    private ClientInterface|null $client = null;

    private SerializerBuilder|null $serializerBuilder = null;

    private Mapper|null $mapper = null;

    private LoggerInterface|null $logger = null;

    private bool $initialized = false;

    private string $baseUri = 'https://api.openbrewerydb.org';

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self;
    }

    public function withSerializerBuilder(SerializerBuilder|null $serializerBuilder): self
    {
        $this->ensureMutable();
        $this->serializerBuilder = $serializerBuilder;
        return $this;
    }

    public function withClient(ClientInterface|null $client): self
    {
        $this->ensureMutable();
        $this->client = $client;
        return $this;
    }

    public function withMapper(Mapper|null $mapper): self
    {
        $this->ensureMutable();
        $this->mapper = $mapper;
        return $this;
    }

    public function withLogger(LoggerInterface|null $logger): self
    {
        $this->ensureMutable();
        $this->logger = $logger;
        return $this;
    }

    public function withBaseUri(string $baseUri): self
    {
        $this->ensureMutable();

        $this->baseUri = $baseUri;
        return $this;
    }

    public function build(): self
    {
        $this->ensureMutable();

        $this->setupSerializerBuilder();
        $this->setupClient();
        $this->setupMapper();

        $this->initialized = true;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function getSerializerBuilder(): ?SerializerBuilder
    {
        return $this->serializerBuilder;
    }

    public function getMapper(): ?Mapper
    {
        return $this->mapper;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    private function setupSerializerBuilder(): void
    {
        if ($this->serializerBuilder !== null) return;

        $this->serializerBuilder = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->setSerializationContextFactory(function () {
                return SerializationContext::create()
                    ->setSerializeNull(true);
            })
            ->addDefaultHandlers();
    }

    private function setupClient(): void
    {
        if ($this->client !== null) return;

        $stack = new HandlerStack();
        $stack->setHandler(Utils::chooseHandler());

        $stack->push(Middleware::httpErrors());

        $this->setupLoggingMiddleware($stack);

        $this->client = new Client([
            'handler' => $stack,
            'base_uri' => $this->baseUri,
            'timeout' => (float)env('BREWERY_CLIENT_TIMEOUT', 300.0)
        ]);

    }

    private function setupLoggingMiddleware(HandlerStack $stack): void
    {
        if(!$this->logger) return;

        $messageFormats = [
            'BODY: {res_body}',
            'STATUS: {code}',
            'RESPONSE: <<<<<<<<<<<<<<',
            'Payload: {req_body}',
            'HEADERS: {req_headers}',
            'HTTP/{version}',
            'URL: {uri}',
            'METHOD: {method}',
            'REQUEST: >>>>>>>>>>>>>>>'
        ];

        collect($messageFormats)->each(function ($messageFormat) use ($stack) {
            $stack->push(
                Middleware::log($this->logger, new MessageFormatter($messageFormat))
            );
        });

    }

    private function setupMapper(): void
    {
        if ($this->mapper !== null) return;

        $this->mapper = new Mapper($this->serializerBuilder);
    }

    private function ensureMutable(): void
    {
        if (!$this->initialized) return;

        throw new \RuntimeException("Context already initialized, properties are now immutable");
    }

}
