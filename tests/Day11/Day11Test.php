<?php

namespace App\Tests\Day11;

use PHPUnit\Framework\TestCase;

class Day11Test extends TestCase
{
    public const int GALAXIES_AGE = 1000000;

    public function testCosmicExpansionPart01()
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $universe = $this->expandUniverse($this->buildUniverse($input));
        $galaxies = $this->findGalaxies($universe);
        $totalDistance = 0;

        for ($i = 0; $i < count($galaxies); $i++) {
            for ($j = $i + 1; $j < count($galaxies); $j++) {
                $totalDistance += $this->calculateDistanceBetween2Galaxies($galaxies[$i], $galaxies[$j]);
            }
        }

        echo $totalDistance;
    }

    public function testCosmicExpansionPart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $universe = $this->buildUniverse($input);
        $galaxies = $this->findGalaxies($universe);
        $totalDistance = 0;
        $emptyRows = $this->findRowsWithoutGalaxy($universe);
        $emptyLines = $this->findLinesWithoutGalaxy($universe);


        for ($i = 0; $i < count($galaxies); $i++) {
            for ($j = $i + 1; $j < count($galaxies); $j++) {
                $totalDistance += $this->calculateDistanceBetween2OldGalaxies($universe, $galaxies[$i], $galaxies[$j], $emptyRows, $emptyLines);
            }
        }

        echo $totalDistance;
    }

    protected function buildUniverse(string $rawImage): array
    {
        $lines = explode("\n", $rawImage);
        $universe = [];

        for ($i = 0; $i < count($lines); $i++) {
            $data = str_split($lines[$i]);
            for ($j = 0; $j < count($data); $j++) {
                $universe[$i][] = $data[$j];
            }
        }

        return $universe;
    }

    protected function expandUniverse(array $universe): array
    {
        return $this->expandRows($this->expandLines($universe));
    }

    protected function findRowsWithoutGalaxy(array $universe): array
    {
        $rowsWithoutGalaxy = [];
        for ($i = 0; $i < count($universe[0]); $i++) {
            for ($j = 0; $j < count($universe); $j++) {
                if ('#' == $universe[$j][$i]) {
                    continue 2;
                }
            }
            $rowsWithoutGalaxy[] = $i;
        }

        return $rowsWithoutGalaxy;
    }

    protected function findLinesWithoutGalaxy(array $universe): array
    {
        $linesWithoutGalaxy = [];
        for ($i = 0; $i < count($universe); $i++) {
            if (count(array_filter($universe[$i], fn(string $data) => '#' == $data)) == 0) {
                $linesWithoutGalaxy[] = $i;
            }
        }

        return $linesWithoutGalaxy;
    }

    public function expandLines(array $universe): array
    {
        $i = 0;
        foreach ($this->findLinesWithoutGalaxy($universe) as $line) {
            $toDuplicate = $universe[($line + $i)];
            $universe = array_merge(array_slice($universe, 0, ($line + $i)), [$toDuplicate], array_slice($universe, ($line + $i)));
            $i++;
        }

        return $universe;
    }

    public function expandRows(array $universe): array
    {
        $i = 0;
        foreach ($this->findRowsWithoutGalaxy($universe) as $row) {
            for ($j = 0; $j < count($universe); $j++) {
                $universe[$j] = array_merge(array_slice($universe[$j], 0, ($row + $i)), ['.'], array_slice($universe[$j], ($row + $i)));
            }
            $i++;
        }
        return $universe;
    }

    protected function findGalaxies(array $universe): array
    {
        $galaxies = [];
        for ($i = 0; $i < count($universe); $i++) {
            for ($j = 0; $j < count($universe[$i]); $j++) {
                if ('#' == $universe[$i][$j]) {
                    $galaxies[] = [$j, $i];
                }
            }
        }

        return $galaxies;
    }

    protected function calculateDistanceBetween2Galaxies(array $from, array $to): int
    {
        return abs($to[0] - $from[0]) + abs($to[1] - $from[1]);
    }

    protected function calculateDistanceBetween2OldGalaxies(array $universe, array $from, array $to, $emptyRows, $emptyLines): int
    {
        $lowestDepartureAbscissa = min($from[0], $to[0]);
        $highestDepartureAbscissa = max($from[0], $to[0]);
        $lowestDepartureOrdinate = min($from[1], $to[1]);
        $highestDepartureOrdinate = max($from[1], $to[1]);

        $emptyRowsCrossed = array_filter($emptyRows, fn(int $row) => $row > $lowestDepartureAbscissa && $row < $highestDepartureAbscissa);
        $emptyLinesCrossed = array_filter($emptyLines, fn(int $line) => $line > $lowestDepartureOrdinate && $line < $highestDepartureOrdinate);

        return $this->calculateDistanceBetween2Galaxies($from, $to) + ((count($emptyRowsCrossed) + count($emptyLinesCrossed)) * (self::GALAXIES_AGE - 1));
    }
}
