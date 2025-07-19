<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\TestAsset;

class HydratorStrategyEntityB
{
    /**
     * @param mixed $field1
     * @param mixed $field2
     */
    public function __construct(private $field1, private $field2)
    {
    }

    /** @return mixed */
    public function getField1()
    {
        return $this->field1;
    }

    /** @return mixed */
    public function getField2()
    {
        return $this->field2;
    }

    /** @param mixed $value */
    public function setField1($value): self
    {
        $this->field1 = $value;
        return $this;
    }

    /** @param mixed $value */
    public function setField2($value): self
    {
        $this->field2 = $value;
        return $this;
    }
}
