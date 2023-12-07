<?php

namespace App\Tests\Day05;

use PHPUnit\Framework\TestCase;

class Day05Test extends TestCase
{
    public function testIfYouGiveASeedAFertilizerPart01(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $parts = explode("\n\n", $input);

        $steps = [];
        $seeds = array_values(
            array_map(
                fn(string $seed) => (int)$seed,
                array_filter(
                    array_map(
                        fn(string $seed) => trim($seed),
                        explode(' ', explode(":", $parts[0])[1])
                    ),
                    fn(string $seed) => $seed !== ''
                )
            )
        );
        unset($parts[0]);
        $parts = array_values($parts);

        for ($i = 0; $i < count($parts); $i++) {
            $lines = explode("\n", $parts[$i]);
            unset($lines[0]);
            foreach ($lines as $line) {
                [$destination, $source, $range] = explode(' ', $line);
                $steps[$i][] = ['from' => (int)$source, 'to' => ((int)$source + ((int)$range - 1)), 'operation' => ((int)$destination - (int)$source)];
            }
        }

        for ($i = 0; $i < count($seeds); $i++) {
            foreach ($steps as $step) {
                foreach ($step as $match) {
                    if ($match['from'] <= $seeds[$i] && $match['to'] >= $seeds[$i]) {
                        $seeds[$i] += $match['operation'];
                        continue 2;
                    }
                }
            }
        }

        echo min($seeds);

    }

    public function testIfYouGiveASeedAFertilizerPart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $parts = explode("\n\n", $input);

        $steps = [];
        $lowest = 99999999999999999999;
        $nextSeeds = [];
        $seeds = array_values(
            array_map(
                fn(string $seed) => (int)$seed,
                array_filter(
                    array_map(
                        fn(string $seed) => trim($seed),
                        explode(' ', explode(":", $parts[0])[1])
                    ),
                    fn(string $seed) => $seed !== ''
                )
            )
        );

        for ($i = 0; $i < count($seeds); $i += 2) {
            $nextSeeds[] = ['from' => $seeds[$i], 'to' => ((int)$seeds[$i] + ((int)$seeds[$i + 1] - 1))];
        }

        for ($i = 0; $i < count($parts); $i++) {
            $lines = explode("\n", $parts[$i]);
            unset($lines[0]);
            foreach ($lines as $line) {
                [$destination, $source, $range] = explode(' ', $line);
                $steps[$i][] = ['from' => (int)$source, 'to' => ((int)$source + ((int)$range - 1)), 'operation' => ((int)$destination - (int)$source)];
            }
        }

        $steps = array_values($steps);

        for ($i = 0; $i < count($steps); $i++) {
            $finalSeeds = array_values($nextSeeds);
            $nextSeeds = [];
            for ($j = 0; $j < count($steps[$i]); $j++) {
                $finalSeeds = array_values($finalSeeds);
                $count = count($finalSeeds);
                for ($k = 0; $k < $count; $k++) {
                    $step = $steps[$i][$j];
                    $seed = $finalSeeds[$k];
                    if (
                        ((int) $seed['from'] >= (int)$step['from'] && (int) $seed['from'] <= (int)$step['to']) ||
                        ((int) $step['from'] >= (int)$seed['from'] && (int) $step['from'] <= (int)$seed['to']) ||
                        ((int) $seed['to'] >= (int)$step['from'] && (int) $seed['to'] <= (int)$step['to']) ||
                        ((int) $step['to'] >= (int)$seed['from'] && (int) $step['to'] <= (int)$seed['to'])
                    ) {
                        $match = ['from' => max((int) $seed['from'], (int) $step['from']), 'to' => min((int) $seed['to'], (int) $step['to'])];
                        if ($match['from'] != $seed['from']) {
                            $finalSeeds[] = ['from' => (int)$seed['from'], 'to' =>(int)$match['from'] - 1];
                            $count++;
                        }
                        if ($match['to'] != $seed['to']) {
                            $finalSeeds[] = ['from' => (int)$match['to'] + 1, 'to' =>(int)$seed['to']];
                            $count++;
                        }
                        $nextSeeds[] = ['from' => ($match['from'] + $step['operation']), 'to' => ($match['to'] + $step['operation'])];
                        unset($finalSeeds[$k]);
                    }
                }

                if ($j == count($steps[$i]) - 1) {
                    $nextSeeds = array_merge(array_values($nextSeeds), array_values($finalSeeds));
                }
            }
        }

        for ($i = 0; $i < count($nextSeeds); $i++) {
            $lowest = min((int) $nextSeeds[$i]['from'], $lowest);
        }

        echo $lowest;
    }
}
