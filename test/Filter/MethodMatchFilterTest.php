<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\Filter;

use Iterator;
use Laminas\Hydrator\Filter\MethodMatchFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MethodMatchFilter::class)]
class MethodMatchFilterTest extends TestCase
{
    /**
     * @return Iterator<(int | string), array<(bool | string)>>
     * @psalm-return Iterator<array{0: string, 1: bool}>
     */
    public static function providerFilter(): Iterator
    {
        yield ['foo', true];
        yield ['bar', false];
        yield ['class::foo', true];
        yield ['class::bar', false];
    }

    #[DataProvider('providerFilter')]
    public function testFilter(string $methodName, bool $expected): void
    {
        $testedInstance = new MethodMatchFilter('foo', false);
        $this->assertSame($expected, $testedInstance->filter($methodName));

        $testedInstance = new MethodMatchFilter('foo', true);
        $this->assertSame(! $expected, $testedInstance->filter($methodName));
    }
}
