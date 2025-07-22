<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\Strategy;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Iterator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(DateTimeFormatterStrategy::class)]
class DateTimeFormatterStrategyTest extends TestCase
{
    public function testHydrate(): void
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals('2014-04-26', $strategy->hydrate('2014-04-26')->format('Y-m-d'));

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new DateTimeZone('Asia/Kathmandu'));

        $date = $strategy->hydrate('2014-04-26');
        $this->assertEquals('Asia/Kathmandu', $date->getTimezone()->getName());
    }

    public function testExtract(): void
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertEquals('26/04/2014', $strategy->extract(new DateTime('2014-04-26')));
    }

    public function testGetNullWithInvalidDateOnHydration(): void
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals(null, $strategy->hydrate(null));
        $this->assertEquals(null, $strategy->hydrate(''));
    }

    public function testCanExtractIfNotDateTime(): void
    {
        $strategy = new DateTimeFormatterStrategy();
        $date     = $strategy->extract(new stdClass());

        $this->assertInstanceOf(stdClass::class, $date);
    }

    public function testCanHydrateWithInvalidDateTime(): void
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertSame('foo bar baz', $strategy->hydrate('foo bar baz'));
    }

    public function testCanExtractAnyDateTimeInterface(): void
    {
        $dateMock = $this
            ->getMockBuilder(DateTime::class)
            ->getMock();

        $format = 'Y-m-d';
        $dateMock
            ->expects($this->once())
            ->method('format')
            ->with($format);

        $dateImmutableMock = $this
            ->getMockBuilder(DateTimeImmutable::class)
            ->getMock();

        $dateImmutableMock
            ->expects($this->once())
            ->method('format')
            ->with($format);

        $strategy = new DateTimeFormatterStrategy($format);

        $strategy->extract($dateMock);
        $strategy->extract($dateImmutableMock);
    }

    #[DataProvider('formatsWithSpecialCharactersProvider')]
    public function testAcceptsCreateFromFormatSpecialCharacters(string $format, string $expectedValue): void
    {
        $strategy = new DateTimeFormatterStrategy($format);
        $hydrated = $strategy->hydrate($expectedValue);

        $this->assertInstanceOf(DateTime::class, $hydrated);
        $this->assertSame($expectedValue, $hydrated->format('Y-m-d'));
    }

    #[DataProvider('formatsWithSpecialCharactersProvider')]
    public function testCanExtractWithCreateFromFormatSpecialCharacters(string $format, string $expectedValue): void
    {
        $date      = DateTime::createFromFormat($format, $expectedValue);
        $strategy  = new DateTimeFormatterStrategy($format);
        $extracted = $strategy->extract($date);

        $this->assertEquals($expectedValue, $extracted);
    }

    public function testCanExtractWithCreateFromFormatEscapedSpecialCharacters(): void
    {
        $date      = DateTime::createFromFormat('Y-m-d', '2018-02-05');
        $strategy  = new DateTimeFormatterStrategy('Y-m-d\\+');
        $extracted = $strategy->extract($date);
        $this->assertEquals('2018-02-05+', $extracted);
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function formatsWithSpecialCharactersProvider(): Iterator
    {
        yield '!-prepended' => ['!Y-m-d', '2018-02-05'];
        yield '|-appended' => ['Y-m-d|', '2018-02-05'];
        yield '+-appended' => ['Y-m-d+', '2018-02-05'];
    }

    public function testCanHydrateWithDateTimeFallback(): void
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d', null, true);
        $date     = $strategy->hydrate('2018-09-06T12:10:30');

        $this->assertInstanceOf(DateTimeInterface::class, $date);
        $this->assertSame('2018-09-06', $date->format('Y-m-d'));

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new DateTimeZone('Europe/Prague'), true);
        $date     = $strategy->hydrate('2018-09-06T12:10:30');

        $this->assertInstanceOf(DateTimeInterface::class, $date);
        $this->assertSame('Europe/Prague', $date->getTimezone()->getName());
    }

    /** @return Iterator<string, list<mixed>> */
    public static function invalidValuesForHydration(): Iterator
    {
        yield 'zero' => [0];
        yield 'int' => [1];
        yield 'zero-float' => [0.0];
        yield 'float' => [1.1];
        yield 'array' => [['2018-11-20']];
        yield 'object' => [(object) ['date' => '2018-11-20']];
    }

    #[DataProvider('invalidValuesForHydration')]
    public function testHydrateRaisesExceptionIfValueIsInvalid(mixed $value): void
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');

        $this->expectException(InvalidArgumentException::class);

        $strategy->hydrate($value);
    }

    /** @return Iterator<string, list<mixed>> */
    public static function validUnHydratableValues(): Iterator
    {
        yield 'empty string' => [''];
        yield 'null' => [null];
        yield 'date-time' => [new DateTimeImmutable('now')];
    }

    #[DataProvider('validUnHydratableValues')]
    public function testReturnsValueVerbatimUnderSpecificConditions(mixed $value): void
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $hydrated = $strategy->hydrate($value);
        $this->assertSame($value, $hydrated);
    }
}
