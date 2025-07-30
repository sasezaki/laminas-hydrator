<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

final class ClassMethodsCamelCaseMissing
{
    private string $fooBar = '1';

    private string $fooBarBaz = '2';

    public function getFooBar(): string
    {
        return $this->fooBar;
    }

    public function setFooBar(string $value): self
    {
        $this->fooBar = $value;
        return $this;
    }

    public function getFooBarBaz(): string
    {
        return $this->fooBarBaz;
    }

    /*
     * comment to detection verification
     *
    public function setFooBarBaz($value)
    {
        $this->fooBarBaz = $value;
        return $this;
    }
    */
}
