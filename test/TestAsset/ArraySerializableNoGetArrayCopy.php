<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

final class ArraySerializableNoGetArrayCopy
{
    private array $data = [
        "foo"   => "bar",
        "bar"   => "foo",
        "blubb" => "baz",
        "quo"   => "blubb",
    ];

    public function __construct()
    {
    }

    /**
     * Exchange internal values from provided array
     */
    public function exchangeArray(array $array): void
    {
        $this->data = $array;
    }

    /**
     * Returns the internal data
     */
    public function getData(): array
    {
        return $this->data;
    }
}
