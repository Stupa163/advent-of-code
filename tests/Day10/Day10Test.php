<?php

namespace App\Tests\Day10;

use PHPUnit\Framework\TestCase;

class Day10Test extends TestCase
{
    public const array PIPES_DIRECTIONS = [
        '|' => [[0, -1], [0, 1]],
        '-' => [[-1, 0], [1, 0]],
        'L' => [[0, -1], [1, 0]],
        'J' => [[-1, 0], [0, -1]],
        '7' => [[-1, 0], [0, 1]],
        'F' => [[0, 1], [1, 0]],
        '.' => [],
        'S' => [[0, -1], [1, 0], [0, 1], [-1, 0]]
    ];

    public function testPipeMazePart01(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $lines = explode("\n", $input);

        $terrain = $this->buildPipeTerrain($lines);
        $startingCoordinates = $this->findStartingCoordinates($terrain);
        $terrain[$startingCoordinates[1]][$startingCoordinates[0]] = $this->determineStartingCoordinatePipe($terrain, $startingCoordinates);

        $position = $startingCoordinates;
        $previousPosition = null;
        $i = 0;

        do {
            $newPosition = $this->findNextPosition($terrain, $position, $previousPosition);
            $previousPosition = $position;
            $position = $newPosition;
            if ($i > 500000) {
                break;
            }
            $i++;
        } while ($position != $startingCoordinates);

        echo($i / 2);
    }

    public function testPipeMazePart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');
        $lines = explode("\n", $input);

        $terrain = $this->buildPipeTerrain($lines);
        $startingDot = $this->findFirstDotOutsideTheMaze($terrain);

    }

    protected function buildPipeTerrain(array $lines): array
    {
        $terrain = [];
        for ($i = 0; $i < count($lines); $i++) {
            $terrain[] = str_split($lines[$i]);
        }

        return $terrain;
    }

    protected function findStartingCoordinates(array $terrain): array
    {
        for ($i = 0; $i < count($terrain); $i++) {
            for ($j = 0; $j < count($terrain[$i]); $j++) {
                if ($terrain[$i][$j] == 'S') {
                    return [$j, $i];
                }
            }
        }

        echo 'No starting point found';
        die();
    }

    protected function determineStartingCoordinatePipe(array $terrain, array $startingCoordinates): string
    {
        $connectedDirections = [];

        if ($this->areTwoPipesConnected($terrain, $startingCoordinates, [$startingCoordinates[0] - 1, $startingCoordinates[1]])) {
            $connectedDirections[] = [-1, 0];
        }
        if ($this->areTwoPipesConnected($terrain, $startingCoordinates, [$startingCoordinates[0] + 1, $startingCoordinates[1]])) {
            $connectedDirections[] = [1, 0];
        }
        if ($this->areTwoPipesConnected($terrain, $startingCoordinates, [$startingCoordinates[0], $startingCoordinates[1] - 1])) {
            $connectedDirections[] = [0, -1];
        }
        if ($this->areTwoPipesConnected($terrain, $startingCoordinates, [$startingCoordinates[0], $startingCoordinates[1] + 1])) {
            $connectedDirections[] = [0, 1];
        }

        foreach (self::PIPES_DIRECTIONS as $pipe => $directions) {
            for ($i = 0; $i < count($directions); $i++) {
                if (!in_array($directions[$i], $connectedDirections)) {
                    continue 2;
                }

                return $pipe;
            }
        }

        return '.';
    }

    protected function areTwoPipesConnected(array $terrain, array $firstPipe, array $secondPipe): bool
    {
        $connectedToFirstPipe = [];
        $connectedToSecondPipe = [];

        foreach (self::PIPES_DIRECTIONS[$terrain[$firstPipe[1]][$firstPipe[0]]] as $direction) {
            $connectedToFirstPipe[] = [$firstPipe[0] + $direction[0], $firstPipe[1] + $direction[1]];
        }
        foreach (self::PIPES_DIRECTIONS[$terrain[$secondPipe[1]][$secondPipe[0]]] as $direction) {
            $connectedToSecondPipe[] = [$secondPipe[0] + $direction[0], $secondPipe[1] + $direction[1]];
        }

        return in_array($secondPipe, $connectedToFirstPipe) && in_array($firstPipe, $connectedToSecondPipe);
    }

    protected function findNextPosition(array $terrain, array $position, ?array $previousPosition = null): array
    {
        $evaluatedPosition = [];
        foreach (self::PIPES_DIRECTIONS[$terrain[$position[1]][$position[0]]] as $pipe => $direction) {
            $evaluatedPosition = [$position[0] + $direction[0], $position[1] + $direction[1]];
            if ($evaluatedPosition != $previousPosition) {
                break;
            }
        }

        return $evaluatedPosition;
    }

    protected function findFirstDotOutsideTheMaze(array $terrain): array
    {
        $dotAbscissa = array_search('.', $terrain[0]);

        return [$dotAbscissa, 0];
    }

    protected function buildMaze(array $terrain, array $startingCoordinates): array
    {
        $maze = [];
        $position = $startingCoordinates;
        $previousPosition = null;
        $i = 0;

        do {
            $newPosition = $this->findNextPosition($terrain, $position, $previousPosition);
            $previousPosition = $position;
            $position = $newPosition;
            $maze[] = $position;
            if ($i > 500000) {
                break;
            }
            $i++;
        } while ($position != $startingCoordinates);

        return $maze;
    }
}
