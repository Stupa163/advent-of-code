<?php

namespace App\Tests\Day04;

use PHPUnit\Framework\TestCase;

class Day04Test extends TestCase
{
    public function testScratchcardsPart01() : void
    {
        $input = file_get_contents(__DIR__.'/Input.txt');

        $sum = 0;

        foreach (explode("\n", $input) as $line) {
            $parts = explode(':', $line)[1];
            [$winningPart, $yourPart] = explode('|', $parts);
            $winnings = array_filter(array_map(fn (string $number) => trim($number), explode(' ', $winningPart)), fn (string $number) => $number !== '');
            $yours = array_filter(array_map(fn (string $number) => trim($number), explode(' ', $yourPart)), fn (string $number) => $number !== '');

            $won = array_intersect($winnings, $yours);
            $sum += count($won) > 0 ? 2 ** (count($won) - 1) : 0;
        }

        echo $sum;
    }

    public function testScratchcardsPart02()
    {
        $input = file_get_contents(__DIR__.'/Input.txt');
        $cards = array_map(fn (string $card) => [$card, 1], explode("\n", $input));

        for ($i = 0; $i < count($cards); $i++) {
            $parts = explode(':', $cards[$i][0])[1];
            [$winningPart, $yourPart] = explode('|', $parts);
            $winnings = array_filter(array_map(fn (string $number) => trim($number), explode(' ', $winningPart)), fn (string $number) => $number !== '');
            $yours = array_filter(array_map(fn (string $number) => trim($number), explode(' ', $yourPart)), fn (string $number) => $number !== '');
            $won = array_intersect($winnings, $yours);

            if (count($won) === 0) {
                continue;
            }

            for ($j = 1; $j <= count($won); $j++) {
                $cards[$i+$j][1] += $cards[$i][1];
            }
        }

        $sum = array_reduce($cards, fn (int $carry, array $cards) => $carry + $cards[1], 0);

        echo $sum;
    }
}
