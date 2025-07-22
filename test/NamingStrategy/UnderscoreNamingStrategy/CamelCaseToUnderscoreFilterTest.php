<?php

declare(strict_types=1);

namespace LaminasTest\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use Iterator;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function extension_loaded;

#[CoversClass(CamelCaseToUnderscoreFilter::class)]
class CamelCaseToUnderscoreFilterTest extends TestCase
{
    #[DataProvider('nonUnicodeProvider')]
    public function testFilterUnderscoresNonUnicodeStrings(string $string, string $expected): void
    {
        $filter = new CamelCaseToUnderscoreFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property        = $reflectionClass->getProperty('pcreUnicodeSupport');
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);

        $this->assertNotSame($string, $filtered);
        $this->assertSame($expected, $filtered);
    }

    #[DataProvider('unicodeProvider')]
    public function testFilterUnderscoresUnicodeStrings(string $string, string $expected): void
    {
        if (! extension_loaded('mbstring')) {
            $this->markTestSkipped('Extension mbstring not available');
        }

        $filter = new CamelCaseToUnderscoreFilter();

        $filtered = $filter->filter($string);

        $this->assertNotSame($string, $filtered);
        $this->assertSame($expected, $filtered);
    }

    #[DataProvider('unicodeProviderWithoutMbStrings')]
    public function testFilterUnderscoresUnicodeStringsWithoutMbStrings(string $string, string $expected): void
    {
        $filter = new CamelCaseToUnderscoreFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property        = $reflectionClass->getProperty('mbStringSupport');
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
        yield 'upcased first letter' => [
            'Camel',
            'camel',
        ];
        yield 'multiple words' => [
            'underscoresMe',
            'underscores_me',
        ];
        yield 'alphanumeric' => [
            'one2Three',
            'one2_three',
        ];
        yield 'multiple uppercased letters and underscores' => [
            'TheseAre_SOME_CamelCASEDWords',
            'these_are_some_camel_cased_words',
        ];
        yield 'alphanumeric multiple up cases' => [
            'one2THR23ree',
            'one2_thr23ree',
        ];
        yield 'lowercased alphanumeric' => [
            'bfd7b82e9cfceaa82704d1c1Foo',
            'bfd7b82e9cfceaa82704d1c1_foo',
        ];
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function unicodeProvider(): Iterator
    {
        yield 'upcased first letter' => [
            'Camel',
            'camel',
        ];
        yield 'multiple words' => [
            'underscoresMe',
            'underscores_me',
        ];
        yield 'alphanumeric' => [
            'one2Three',
            'one2_three',
        ];
        yield 'multiple uppercased letters and underscores' => [
            'TheseAre_SOME_CamelCASEDWords',
            'these_are_some_camel_cased_words',
        ];
        yield 'alphanumeric multiple up cases' => [
            'one2THR23ree',
            'one2_thr23ree',
        ];
        yield 'unicode' => [
            'testŠuma',
            'test_šuma',
        ];
    }

    /**
     * @return Iterator<(int | string), array<string>>
     * @psalm-return Iterator<string, array{0: string, 1: string}>
     */
    public static function unicodeProviderWithoutMbStrings(): Iterator
    {
        yield 'upcased first letter' => [
            'Camel',
            'camel',
        ];
        yield 'multiple words' => [
            'underscoresMe',
            'underscores_me',
        ];
        yield 'alphanumeric' => [
            'one2Three',
            'one2_three',
        ];
        yield 'multiple uppercased letters and underscores' => [
            'TheseAre_SOME_CamelCASEDWords',
            'these_are_some_camel_cased_words',
        ];
        yield 'alphanumeric multiple up cases' => [
            'one2THR23ree',
            'one2_thr23ree',
        ];
        yield 'unicode uppercase character' => [
            'testŠuma',
            'test_Šuma',
        ];
    }
}
