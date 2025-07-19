<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class HydratorClosureStrategyEntity
{
    /**
     * @param mixed $field1
     * @param mixed $field2
     */
    public function __construct(public $field1 = null, public $field2 = null)
    {
    }
}
