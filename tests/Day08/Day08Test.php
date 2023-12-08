<?php

namespace App\Tests\Day08;

use PHPUnit\Framework\TestCase;

class Day08Test extends TestCase
{
    public function testHauntedWastelandPart01()
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');

        [$rawInstructions, $rawDirections] = explode("\n\n", $input);
        $instructions = str_split($rawInstructions);
        $directions = $this->formatDirections($rawDirections);
        $currentLocation = 'AAA';
        $instructionsCount = count($instructions);
        $i = 0;

        while ($currentLocation != 'ZZZ') {
            $currentLocation = $directions[$currentLocation][$instructions[$i % $instructionsCount]];
            $i++;
        }

        echo $i;
    }

    public function testHauntedWastelandPart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');

        [$rawInstructions, $rawDirections] = explode("\n\n", $input);
        $instructions = str_split($rawInstructions);
        $directions = $this->formatDirections($rawDirections);
        $currentLocations = array_filter(array_keys($directions), fn(string $location) => str_ends_with($location, 'A'));
        $lcm = $this->findLeastCommonMultipleOfMultipleNumbers(...$this->findAllIndividualsSolutions($currentLocations, $directions, $instructions));

        echo $lcm;
    }

    protected function formatDirections(string $rawDirections): array
    {
        $directions = [];
        foreach (explode("\n", $rawDirections) as $direction) {
            [$key, $to] = explode(' = ', $direction);
            $to = array_map(fn(string $part) => trim($part, '()'), explode(', ', $to));
            $directions[$key] = ['L' => $to[0], 'R' => $to[1]];
        }

        return $directions;
    }

    protected function findAllIndividualsSolutions(array $currentLocations, array $directions, array $instructions): array
    {
        $instructionsCount = count($instructions);
        $solutions = [];
        foreach ($currentLocations as $currentLocation) {
            $i = 0;
            while (!str_ends_with($currentLocation, 'Z')) {
                $currentLocation = $directions[$currentLocation][$instructions[$i % $instructionsCount]];
                $i++;
            }
            $solutions[] = $i;
        }

        return $solutions;
    }

    protected function findGreatestCommonDivisor(int $a, int $b): int
    {
        return $b == 0 ? $a : $this->findGreatestCommonDivisor($b, $a % $b);
    }

    protected function findLeastCommonMultipleOfMultipleNumbers(int ...$numbers): int
    {
        $lcm = $numbers[0];
        for ($i = 1; $i < count($numbers); $i++) {
            $lcm = (($numbers[$i] * $lcm) / $this->findGreatestCommonDivisor($numbers[$i], $lcm));
        }

        return $lcm;
    }
}
