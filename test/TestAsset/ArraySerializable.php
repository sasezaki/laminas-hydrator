<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

use Laminas\Stdlib\ArraySerializableInterface;

final class ArraySerializable implements ArraySerializableInterface
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
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->data;
    }
}
