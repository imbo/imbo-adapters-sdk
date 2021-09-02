<?php declare(strict_types=1);
namespace Imbo\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use RuntimeException;

class MultidimensionalArrayIsEqual extends Constraint
{
    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return 'is the same as ' . $this->exporter()->export($this->value);
    }

    public function matches($other): bool
    {
        if (!is_array($other)) {
            throw new RuntimeException('Can only compare arrays');
        }

        return [] === $this->getArrayDiff($this->value, $other);
    }

    private function getArrayDiff(array $expected, array $actual): array
    {
        $diff = [];

        foreach ($expected as $key => $value) {
            if (!array_key_exists($key, $actual)) {
                $diff[$key] = $value;
            } elseif (is_array($value)) {
                if (!is_array($actual[$key])) {
                    $diff[$key] = $value;
                } else {
                    $subDiff = $this->getArrayDiff($value, $actual[$key]);

                    if (count($subDiff)) {
                        $diff[$key] = $subDiff;
                    }
                }
            } elseif ($actual[$key] !== $value) {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }

    protected function additionalFailureDescription($other): string
    {
        return 'Array difference: ' . $this->exporter()->export($this->getArrayDiff($this->value, $other));
    }
}
