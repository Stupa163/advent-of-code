<?php

namespace App\Tests\Day03;

use PHPUnit\Framework\TestCase;

class Day03Test extends TestCase
{
    private array $schema;

    public function testGearRatiosPart01(): void
    {
        $input = file_get_contents(__DIR__.'/Input.txt');
        $this->schema = $this->constructSchemaFromInput($input);

        $sum = 0;

        for ($i = 0; $i < count($this->schema); $i++) {
            for ($j = 0; $j < count($this->schema[$i]); $j++) {
                if (!is_numeric($this->schema[$i][$j])) {
                    continue;
                }
                if ($this->isAdjacentToSymbol($j, $i)) {
                    [$num, $rEnd] = $this->completeNumberFromLeftToRight($j, $i);
                    $sum += $num;
                    $j = $rEnd;
                }
            }
        }

        echo $sum;
    }

    public function testGearRatiosPart02(): void
    {
        $input = file_get_contents(__DIR__.'/Input.txt');
        $this->schema = $this->constructSchemaFromInput($input);

        $sum = 0;

        for ($i = 0; $i < count($this->schema); $i++) {
            for ($j = 0; $j < count($this->schema[$i]); $j++) {
                if ('*' !== $this->schema[$i][$j]) {
                    continue;
                }

                [$count, $positions] = $this->countAdjacentPartNumberAndFindTheirPositions($j, $i);
                if (2 === $count) {
                    $number = null;
                    for ($y = 0; $y < count($positions); $y++) {
                        for ($x = 0; $x < count($positions[$y]); $x++) {
                            if (1 === $positions[$y][$x]) {
                                $completeNumber = $this->completeNumberFromLeftToRight(($j+($x-1)), $i+($y-1))[0];
                                if (null === $number) {
                                    $number = $completeNumber;
                                } else {
                                    $sum += (int) $number * (int) $completeNumber;
                                    continue 3;
                                }
                            }
                        }
                    }
                }
            }
        }

        echo $sum;
    }

    protected function constructSchemaFromInput(string $input): array
    {
        $schema = [];
        $lines = explode("\n", $input);
        for ($i = 0; $i < count($lines); $i++) {
            $char = str_split($lines[$i]);
            for ($j = 0; $j < count($char); $j++){
                $schema[$i][] = $char[$j];
            }
        }

        return $schema;
    }

    protected function isAdjacentToSymbol(int $x, int $y): bool
    {
        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                if (null !== $this->schema[$j][$i] && '.' !== $this->schema[$j][$i] && !is_numeric($this->schema[$j][$i])) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function completeNumberFromLeftToRight(int $x, int $y) : array
    {
        $cloneX = $x;

        do {
            $x--;
        } while (is_numeric($this->schema[$y][$x]));

        $lEnd = $x + 1;
        $x = $cloneX;

        do {
            $x++;
        } while (is_numeric($this->schema[$y][$x]));

        $rEnd = $x - 1;

        $number = implode('', array_slice($this->schema[$y], $lEnd, ($rEnd - $lEnd + 1)));

        return [$number, $rEnd];
    }

    protected function countAdjacentPartNumberAndFindTheirPositions(int $x, int $y): array
    {
        $count = 0;
        $positions = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];

        if (is_numeric($this->schema[$y-1][$x-1]) || is_numeric($this->schema[$y-1][$x]) || is_numeric($this->schema[$y-1][$x+1])) {
            if (is_numeric($this->schema[$y-1][$x-1]) && '.' === $this->schema[$y-1][$x] && is_numeric($this->schema[$y-1][$x+1])) {
                $count += 2;
                $positions[0] = [1, 0, 1];
            } elseif (is_numeric($this->schema[$y-1][$x-1])) {
                $count += 1;
                $positions[0] = [1, 0, 0];
            } elseif (is_numeric($this->schema[$y-1][$x])) {
                $count += 1;
                $positions[0] = [0, 1, 0];
            } elseif (is_numeric($this->schema[$y-1][$x+1])) {
                $count += 1;
                $positions[0] = [0, 0, 1];
            }
        }

        if (is_numeric($this->schema[$y][$x-1])) {
            $count += 1;
            $positions[1][0] = 1;
        }
        if (is_numeric($this->schema[$y][$x+1])) {
            $count += 1;
            $positions[1][2] = 1;
        }

        if (is_numeric($this->schema[$y+1][$x-1]) || is_numeric($this->schema[$y+1][$x]) || is_numeric($this->schema[$y+1][$x+1])) {
            if (is_numeric($this->schema[$y+1][$x-1]) && '.' === $this->schema[$y+1][$x] && is_numeric($this->schema[$y+1][$x+1])) {
                $count += 2;
                $positions[2] = [1, 0, 1];
            } elseif (is_numeric($this->schema[$y+1][$x-1])) {
                $count += 1;
                $positions[2] = [1, 0, 0];
            } elseif (is_numeric($this->schema[$y+1][$x])) {
                $count += 1;
                $positions[2] = [0, 1, 0];
            } elseif (is_numeric($this->schema[$y+1][$x+1])) {
                $count += 1;
                $positions[2] = [0, 0, 1];
            }
        }

        return [$count, $positions];
    }
}
