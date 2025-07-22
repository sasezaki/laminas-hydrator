<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\NamingStrategy;

use Iterator;
use Laminas\Hydrator\NamingStrategy\IdentityNamingStrategy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(IdentityNamingStrategy::class)]
class IdentityNamingStrategyTest extends TestCase
{
    #[DataProvider('getTestedNames')]
    public function testHydrate(string $name): void
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->hydrate($name));
    }

    #[DataProvider('getTestedNames')]
    public function testExtract(string $name): void
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->extract($name));
    }

    /**
     * Data provider
     *
     * @return Iterator<(int | string), array<string>>
     */
    public static function getTestedNames(): Iterator
    {
        yield 'foo' => ['foo'];
        yield 'bar' => ['bar'];
    }
}
