<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ObjectProperty
{
    public string $foo = 'bar';

    public string $bar = 'foo';

    public string $blubb = 'baz';

    public string $quo = 'blubb';

    protected string $quin = 'five';

    public function __construct()
    {
    }

    public function get(string $name): string
    {
        return $this->$name;
    }
}
