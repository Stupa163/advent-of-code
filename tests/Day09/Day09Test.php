<?php

namespace App\Tests\Day09;

use PHPUnit\Framework\TestCase;

class Day09Test extends TestCase
{
    public function testMirageMaintenancePart01(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');

        $lines = explode("\n", $input);
        $predictions = [];

        for ($i = 0; $i < count($lines); $i++) {
            $sequence = array_map(fn(string $value) => (int)$value, explode(' ', $lines[$i]));
            $predictions[] = $this->predictNextValue($sequence);
        }

        echo array_sum($predictions);
    }

    public function testMirageMaintenancePart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $lines = explode("\n", $input);
        $predictions = [];

        for ($i = 0; $i < count($lines); $i++) {
            $sequence = array_map(fn(string $value) => (int)$value, explode(' ', $lines[$i]));
            $predictions[] = $this->predictNextValue($sequence, true);
        }

        echo array_sum($predictions);
    }

    protected function predictNextValue(array $sequence, bool $fromBeginning = false): int
    {
        $history = [$sequence];
        while (!(1 == count(array_unique($sequence)) && $sequence[0] == 0)) {
            $nextSequence = [];
            for ($i = 1; $i < count($sequence); $i++) {
                $nextSequence[] = $sequence[$i] - $sequence[$i - 1];
            }
            $sequence = $nextSequence;
            $history[] = $nextSequence;
        }

        if (!$fromBeginning) {
            return $this->extrapolateHistory(array_map(fn(array $sequence) => end($sequence), $history));
        }

        return $this->extrapolateHistoryFromBeginning(array_reverse(array_map(fn(array $sequence) => current($sequence), $history)));
    }

    protected function extrapolateHistory(array $history): int
    {
        return array_sum($history);
    }

    protected function extrapolateHistoryFromBeginning(array $history): int
    {
        return array_reduce($history, fn(int $carry, int $value) => $value - $carry, 0);
    }
}
