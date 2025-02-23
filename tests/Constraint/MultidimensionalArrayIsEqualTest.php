<?php declare(strict_types=1);
namespace Imbo\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(MultidimensionalArrayIsEqual::class)]
class MultidimensionalArrayIsEqualTest extends TestCase
{
    /**
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     */
    #[DataProvider('getArrays')]
    public function testCanCompareArrays(array $expected, array $actual): void
    {
        $constraint = new MultidimensionalArrayIsEqual($expected);
        $this->assertTrue($constraint->matches($actual));
    }

    public function testCanOnlyMatchArray(): void
    {
        $this->expectException(RuntimeException::class);
        $constraint = new MultidimensionalArrayIsEqual([]);
        $constraint->matches('some string');
    }

    public function testRendersErrorMessage(): void
    {
        $constraint = new MultidimensionalArrayIsEqual(['foo' => 'bar']);
        $this->assertStringStartsWith('is the same as Array', $constraint->toString());
    }

    /**
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     */
    #[DataProvider('getArraysForFailure')]
    public function testCanFail(array $expected, array $actual): void
    {
        $constraint = new MultidimensionalArrayIsEqual($expected);
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Array difference');
        $constraint->evaluate($actual);
    }

    /**
     * @return array<string,array{expected:array<mixed>,actual:array<mixed>}>
     */
    public static function getArrays(): array
    {
        return [
            'empty arrays' => [
                'expected' => [],
                'actual' => [],
            ],
            'assoc' => [
                'expected' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                    'key3' => 'value3',
                ],
                'actual' => [
                    'key2' => 'value2',
                    'key3' => 'value3',
                    'key1' => 'value1',
                ],
            ],
            'multidimensional assoc' => [
                'expected' => [
                    'key1' => 'value1',
                    'key2' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                        'key3' => 'value3',
                    ],
                    'key3' => 'value3',
                ],
                'actual' => [
                    'key2' => [
                        'key2' => 'value2',
                        'key3' => 'value3',
                        'key1' => 'value1',
                    ],
                    'key1' => 'value1',
                    'key3' => 'value3',
                ],
            ],
        ];
    }

    /**
     * @return array<string,array{expected:array<mixed>,actual:array<mixed>}>
     */
    public static function getArraysForFailure(): array
    {
        return [
            'missing keys' => [
                'expected' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                    'key3' => [
                        'key1' => 'value1',
                    ],
                ],
                'actual' => [
                    'key1' => 'value1',
                    'key3' => 'some value',
                ],
            ],
            'incorrect value' => [
                'expected' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                'actual' => [
                    'key1' => 'value1',
                    'key2' => 'value3',
                ],
            ],
        ];
    }
}
