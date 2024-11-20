<?php

namespace App\Brewery\Mapper;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class Mapper
{

    private readonly SerializerBuilder $builder;

    private SerializerInterface $serializer;

    /**
     * @param SerializerBuilder $builder
     */
    public function __construct(SerializerBuilder $builder)
    {
        $this->builder = $builder;
        $this->setupSerializerBuilder();
    }

    /**
     * Serialize body to json
     *
     * @param mixed $body
     * @return string
     */
    public function serialize(mixed $body): string
    {
        if (!$body) return $body;

        return $this->serializer->serialize($body, 'json');
    }

    /**
     * Deserialize json into Clazz object
     *
     * @param string|null $serialized
     * @param string $clazz
     * @return mixed
     */
    public function deserialize(?string $serialized, string $clazz): mixed
    {
        if (!$serialized) return $serialized;

        return $this->serializer->deserialize($serialized, $clazz, 'json');
    }

    private function setupSerializerBuilder(): void
    {
        $this->serializer = $this->builder
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->setSerializationContextFactory(function () {
                $ctx = SerializationContext::create();

                $ctx->setSerializeNull(true);

                return $ctx;
            })
            ->build();
    }

}
