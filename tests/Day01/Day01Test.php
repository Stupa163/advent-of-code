<?php

declare(strict_types=1);

namespace App\Tests\Day01;

use App\Tests\GetInputTrait;
use PHPUnit\Framework\TestCase;

class Day01Test extends TestCase
{
    public function testTrebuchetPart01(): void {
        $input = file_get_contents(__DIR__.'/Input.txt');
        preg_match_all('/([0-9]|\n)/m', $input, $digits);
        $sum = 0;

        $firstDigit = $digits[0][0];
        for ($i = 0; $i < count($digits[0]); $i++) {
            if ($digits[0][$i] !== "\n") {
                continue;
            }
            $sum += (int) ($firstDigit.$digits[0][$i-1]);
            $firstDigit = $digits[0][$i+1];
            $i++;
        }
        $sum += (int) ($firstDigit.$digits[0][$i-1]);

        echo $sum;
    }

    public function testTrebuchetPart02(): void {
        $replacements = [
            'eightwo' => '82',
            'eighthree' => '83',
            'nineight' => '98',
            'twone' => '21',
            'oneight' => '18',
            'threeight' => '38',
            'fiveight' => '58',
            'sevenine' => '79',
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
            'five' => '5',
            'six' => '6',
            'seven' => '7',
            'eight' => '8',
            'nine' => '9',
        ];

        $input = file_get_contents(__DIR__.'/Input.txt');
        $replacedInput = str_replace(array_keys($replacements), array_values($replacements), $input);
        preg_match_all('/([0-9]|\n)/m', $replacedInput, $digits);
        $sum = 0;

        $firstDigit = $digits[0][0];
        for ($i = 0; $i < count($digits[0]); $i++) {
            if ($digits[0][$i] !== "\n") {
                continue;
            }
            $sum += (int) ($firstDigit.$digits[0][$i-1]);
            $firstDigit = $digits[0][$i+1];
            $i++;
        }
        $sum += (int) ($firstDigit.$digits[0][$i-1]);

        echo $sum;
    }
}