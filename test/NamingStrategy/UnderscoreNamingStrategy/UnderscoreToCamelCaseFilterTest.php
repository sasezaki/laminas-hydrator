<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use Iterator;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function extension_loaded;

#[CoversClass(UnderscoreToCamelCaseFilter::class)]
class UnderscoreToCamelCaseFilterTest extends TestCase
{
    #[DataProvider('nonUnicodeProvider')]
    public function testFilterCamelCasesNonUnicodeStrings(string $string, string $expected): void
    {
        $filter = new UnderscoreToCamelCaseFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property        = $reflectionClass->getProperty('pcreUnicodeSupport');
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);

        $this->assertNotSame($string, $filtered);
        $this->assertSame($expected, $filtered);
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function nonUnicodeProvider(): Iterator
    {
        yield 'one word' => [
            'Studly',
            'studly',
        ];
        yield 'multiple words' => [
            'studly_cases_me',
            'studlyCasesMe',
        ];
        yield 'alphanumeric in single word' => [
            'one_2_three',
            'one2Three',
        ];
        yield 'alphanumeric in separate words' => [
            'one2_three',
            'one2Three',
        ];
    }

    #[DataProvider('unicodeProvider')]
    public function testFilterCamelCasesUnicodeStrings(string $string, string $expected): void
    {
        if (! extension_loaded('mbstring')) {
            $this->markTestSkipped('Extension mbstring not available');
        }

        $filter   = new UnderscoreToCamelCaseFilter();
        $filtered = $filter->filter($string);

        $this->assertNotSame($string, $filtered);
        $this->assertSame($expected, $filtered);
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function unicodeProvider(): Iterator
    {
        yield 'uppercase first letter' => [
            'Camel',
            'camel',
        ];
        yield 'multiple words' => [
            'studly_cases_me',
            'studlyCasesMe',
        ];
        yield 'alphanumeric in single word' => [
            'one_2_three',
            'one2Three',
        ];
        yield 'alphanumeric in separate words' => [
            'one2_three',
            'one2Three',
        ];
        yield 'unicode character' => [
            'test_Šuma',
            'testŠuma',
        ];
        yield 'unicode character [Laminas-10517]' => [
            'test_šuma',
            'testŠuma',
        ];
    }

    #[DataProvider('unicodeWithoutMbStringsProvider')]
    public function testFilterCamelCasesUnicodeStringsWithoutMbStrings(
        string $string,
        string $expected
    ): void {
        $filter = new UnderscoreToCamelCaseFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property        = $reflectionClass->getProperty('mbStringSupport');
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);
        $this->assertSame($expected, $filtered);
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function unicodeWithoutMbStringsProvider(): Iterator
    {
        yield 'multiple words' => [
            'studly_cases_me',
            'studlyCasesMe',
        ];
        yield 'alphanumeric in single word' => [
            'one_2_three',
            'one2Three',
        ];
        yield 'alphanumeric in separate words' => [
            'one2_three',
            'one2Three',
        ];
        yield 'uppercase unicode character' => [
            'test_Šuma',
            'testŠuma',
        ];
        yield 'lowercase unicode character' => [
            'test_šuma',
            'test_šuma',
        ];
    }
}
