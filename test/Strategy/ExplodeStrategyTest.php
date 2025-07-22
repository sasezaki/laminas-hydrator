<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\Strategy;

use Iterator;
use Laminas\Hydrator\Strategy\Exception\InvalidArgumentException;
use Laminas\Hydrator\Strategy\ExplodeStrategy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function is_numeric;

#[CoversClass(ExplodeStrategy::class)]
class ExplodeStrategyTest extends TestCase
{
    /**
     * @param non-empty-string $delimiter
     * @param string[] $extractValue
     */
    #[DataProvider('getValidHydratedValues')]
    public function testExtract(mixed $expected, string $delimiter, array $extractValue): void
    {
        $strategy = new ExplodeStrategy($delimiter);

        if (is_numeric($expected)) {
            $this->assertEquals($expected, $strategy->extract($extractValue));
        } else {
            $this->assertSame($expected, $strategy->extract($extractValue));
        }
    }

    public function testGetExceptionWithInvalidArgumentOnExtraction(): void
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);

        /** @psalm-suppress InvalidArgument */
        $strategy->extract('');
    }

    public function testGetEmptyArrayWhenHydratingNullValue(): void
    {
        $strategy = new ExplodeStrategy();

        $this->assertSame([], $strategy->hydrate(null));
    }

    public function testGetExceptionWithEmptyDelimiter(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @psalm-suppress InvalidArgument */
        new ExplodeStrategy('');
    }

    public function testHydrateWithExplodeLimit(): void
    {
        $strategy = new ExplodeStrategy('-', 2);
        $this->assertSame(['foo', 'bar-baz-bat'], $strategy->hydrate('foo-bar-baz-bat'));

        $strategy = new ExplodeStrategy('-', 3);
        $this->assertSame(['foo', 'bar', 'baz-bat'], $strategy->hydrate('foo-bar-baz-bat'));
    }

    public function testHydrateWithInvalidScalarType(): void
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Laminas\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' array provided instead'
        );

        $strategy->hydrate([]);
    }

    public function testHydrateWithInvalidObjectType(): void
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Laminas\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' stdClass provided instead'
        );

        $strategy->hydrate(new stdClass());
    }

    public function testExtractWithInvalidObjectType(): void
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Laminas\Hydrator\Strategy\ExplodeStrategy::extract expects argument 1 to be array,'
            . ' stdClass provided instead'
        );

        /** @psalm-suppress InvalidArgument */
        $strategy->extract(new stdClass());
    }

    /**
     * @param non-empty-string $delimiter
     */
    #[DataProvider('getValidHydratedValues')]
    public function testHydration(mixed $value, string $delimiter, array $expected): void
    {
        $strategy = new ExplodeStrategy($delimiter);

        $this->assertSame($expected, $strategy->hydrate($value));
    }

    /**
     * @return Iterator<string, array{mixed, non-empty-string, list<string>}>
     */
    public static function getValidHydratedValues(): Iterator
    {
        // @codingStandardsIgnoreEnd
        // @codingStandardsIgnoreStart
        yield 'null-comma' => [null, ',', []];
        yield 'empty-comma' => ['', ',', ['']];
        yield 'string without delimiter-comma' => ['foo', ',', ['foo']];
        yield 'string with delimiter-comma' => ['foo,bar', ',', ['foo', 'bar']];
        yield 'string with delimiter-period' => ['foo.bar', '.', ['foo', 'bar']];
        yield 'string with mismatched delimiter-comma' => ['foo.bar', ',', ['foo.bar']];
        yield 'integer-comma' => [123, ',', ['123']];
        yield 'integer-numeric delimiter' => [123, '2', ['1', '3']];
        yield 'integer with mismatched delimiter-comma' => [123.456, ',', ['123.456']];
        yield 'float-period' => [123.456, '.', ['123', '456']];
        yield 'string containing null-comma' => ['foo,bar,dev,null', ',', ['foo', 'bar', 'dev', 'null']];
        yield 'string containing null-semicolon' => ['foo;bar;dev;null', ';', ['foo', 'bar', 'dev', 'null']];
    }
}
