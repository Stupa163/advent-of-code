<?php

namespace App\Tests\Day06;

use PHPUnit\Framework\TestCase;

class Day06Test extends TestCase
{
    public function testWaitForItPart01(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        [$time, $distance] = explode("\n", $input);
        $product = [];

        $times = array_values(array_filter(explode(' ', explode(':', $time)[1]), fn (string $part) => trim($part) !== ''));
        $distances = array_values(array_filter(explode(' ', explode(':', $distance)[1]), fn (string $part) => trim($part) !== ''));

        for ($i = 0; $i < count($times); $i++) {
            $race = 0;
            for ($j = 1; $j < (int) $times[$i]; $j++) {
                $timeLeft = (int) $times[$i] - $j;
                if ($timeLeft * $j > (int) $distances[$i]) {
                    $race++;
                }
            }
            $product[]= $race;
        }

        echo array_product($product);
    }

    public function testWaitForItPart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        [$time, $distance] = explode("\n", $input);

        preg_match_all('/[0-9]/', $time, $timeExplode);
        preg_match_all('/[0-9]/', $distance, $distanceExplode);
        $time = implode($timeExplode[0]).PHP_EOL;
        $distance = implode($distanceExplode[0]).PHP_EOL;

        $race = 0;
        for ($j = 1; $j < (int) $time; $j++) {
            $timeLeft = (int) $time - $j;
            if ($timeLeft * $j > (int) $distance) {
                $race++;
            }
        }
        echo $race;
    }
}
