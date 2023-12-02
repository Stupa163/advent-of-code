<?php

namespace App\Tests\Day02;

use PHPUnit\Framework\TestCase;

class Day02Test extends TestCase
{
    public function testCubeConundrumPart01(): void
    {
        $input = file_get_contents(__DIR__.'/Input.txt');
        $games = explode("\n", $input);
        $sum = 0;
        foreach ($games as $game) {
            [$rawId, $sets] = explode(':', $game);
            $id = preg_replace('/[^0-9.]+/', '', $rawId);
            foreach (explode(',', str_replace(';', ',', $sets)) as $cube) {
                $cube = ltrim($cube, ' ');
                [$count, $color] = explode(' ', $cube);
                switch ($color) {
                    case 'red':
                        if ($count > 12) {
                            continue 3;
                        }
                        break;
                    case 'green':
                        if ($count > 13) {
                            continue 3;
                        }
                        break;
                    case 'blue':
                        if ($count > 14) {
                            continue 3;
                        }
                        break;
                }
            }
            $sum += (int) $id;
        }
        echo $sum;
    }

    public function testCubeConundrumPart02(): void
    {
        $input = file_get_contents(__DIR__.'/Input.txt');
        $games = explode("\n", $input);
        $power = 0;
        foreach ($games as $game) {
            $largestSet = ['red' => 0, 'green' => 0, 'blue' => 0];
            $sets = explode(':', $game)[1];
            foreach (explode(',', str_replace(';', ',', $sets)) as $cube) {
                $cube = ltrim($cube, ' ');
                [$count, $color] = explode(' ', $cube);
                if ($largestSet[$color] < $count) {
                    $largestSet[$color] = $count;
                }

            }
            $power += array_product($largestSet);
        }
        echo $power;
    }
}
