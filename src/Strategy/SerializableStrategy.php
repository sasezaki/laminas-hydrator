<?php

declare(strict_types=1);

namespace Laminas\Hydrator\Strategy;

use Laminas\Serializer\Adapter\AdapterInterface as SerializerAdapter;

final class SerializableStrategy implements StrategyInterface
{
    public function __construct(private readonly SerializerAdapter $serializer)
    {
    }

    /**
     * Serialize the given value so that it can be extracted by the hydrator.
     *
     * {@inheritDoc}
     */
    public function extract($value, ?object $object = null)
    {
        return $this->serializer->serialize($value);
    }

    /**
     * Unserialize the given value so that it can be hydrated by the hydrator.
     *
     * {@inheritDoc}
     */
    public function hydrate($value, ?array $data = null)
    {
        return $this->serializer->unserialize($value);
    }
}
