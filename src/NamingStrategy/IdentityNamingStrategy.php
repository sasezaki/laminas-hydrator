<?php

declare(strict_types=1);

namespace Laminas\Hydrator\NamingStrategy;

final class IdentityNamingStrategy implements NamingStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function hydrate(string $name, ?array $data = null): string
    {
        return $name;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function extract(string $name, ?object $object = null): string
    {
        return $name;
    }
}
