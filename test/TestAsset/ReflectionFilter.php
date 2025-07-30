<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

class ReflectionFilter
{
    protected string $foo = 'bar';

    protected string $bar = 'foo';

    protected string $blubb = 'baz';

    protected string $quo = 'blubb';

    public function __construct()
    {
    }
}
