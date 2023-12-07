<?php

namespace App\Tests\Day07;

use PHPUnit\Framework\TestCase;

class Day07Test extends TestCase
{
    public const array MATCHING_PART_01 = ['A' => 14, 'K' => 13, 'Q' => 12, 'J' => 11, 'T' => 10, '9' => 9, '8' => 8, '7' => 7, '6' => 6, '5' => 5, '4' => 4, '3' => 3, '2' => 2];
    public const array MATCHING_PART_02 = ['A' => 14, 'K' => 13, 'Q' => 12, 'T' => 10, '9' => 9, '8' => 8, '7' => 7, '6' => 6, '5' => 5, '4' => 4, '3' => 3, '2' => 2, 'J' => 1];

    public function testCamelCardsPart01(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');

        $lines = explode("\n", $input);
        $hands = [];
        $sum = 0;

        for ($i = 0; $i < count($lines); $i++) {
            [$hand, $bid] = explode(' ', $lines[$i]);
            $cards = array_map(fn(string $card) => self::MATCHING_PART_01[$card], str_split($hand));
            $hands[] = ['cards' => $cards, 'strength' => $this->getCombinationStrength($cards), 'bid' => $bid];
        }

        usort($hands, [self::class, 'sortByStrengthAndFirstCardFromLeft']);

        for ($i = 0; $i < count($hands); $i++) {
            $sum += ((int)$hands[$i]['bid'] * ($i + 1));
        }

        echo $sum;
    }

    public function testCamelCardsPart02(): void
    {
        $input = file_get_contents(__DIR__ . '/Input.txt');

        $lines = explode("\n", $input);
        $hands = [];
        $sum = 0;

        for ($i = 0; $i < count($lines); $i++) {
            [$hand, $bid] = explode(' ', $lines[$i]);
            $cards = array_map(fn(string $card) => self::MATCHING_PART_02[$card], str_split($hand));

            $replacedCards = $this->findBestHand($cards);
            $hands[] = ['cards' => $cards, 'strength' => $this->getCombinationStrength($replacedCards), 'bid' => $bid];
        }

        usort($hands, [self::class, 'sortByStrengthAndFirstCardFromLeft']);

        for ($i = 0; $i < count($hands); $i++) {
            $sum += ((int)$hands[$i]['bid'] * ($i + 1));
        }

        echo $sum;
    }

    protected function findBestHand(array $cards): array
    {
        $bestStrength = [0];
        $originalCards = $cards;
        $firstJokerKey = array_search(self::MATCHING_PART_02['J'], $cards);

        if (false === $firstJokerKey) {
            return $cards;
        }

        foreach (self::MATCHING_PART_02 as $replacement) {
            if ($replacement == self::MATCHING_PART_02['J']) {
                continue;
            }
            $cards = $originalCards;
            $cards[$firstJokerKey] = $replacement;
            $hand = $this->findBestHand($cards);
            $strength = $this->getCombinationStrength($hand);
            $bestStrength = $strength > $bestStrength[0] ? [$strength, $hand] : $bestStrength;
        }

        return $bestStrength[1];
    }

    protected function getCombinationStrength(array $cards): int
    {
        if ($this->isFiveOfAKind($cards)) {
            return 7;
        }
        if ($this->isFourOfAKind($cards)) {
            return 6;
        }
        if ($this->isFullHouse($cards)) {
            return 5;
        }
        if ($this->isThreeOfAKind($cards)) {
            return 4;
        }
        if ($this->isTwoPairs($cards)) {
            return 3;
        }
        if ($this->isOnePair($cards)) {
            return 2;
        }

        return 1;
    }

    protected function isFiveOfAKind(array $cards): bool
    {
        return count(array_unique($cards)) == 1;
    }

    protected function isFourOfAKind(array $cards): bool
    {
        sort($cards);

        return
            ($cards[0] == $cards[1] && $cards[1] == $cards[2] && $cards[2] == $cards[3]) ||
            ($cards[1] == $cards[2] && $cards[2] == $cards[3] && $cards[3] == $cards[4]);
    }

    protected function isFullHouse(array $cards): bool
    {
        sort($cards);

        return
            ($cards[0] == $cards[1] && $cards[1] == $cards[2] && $cards[3] == $cards[4]) ||
            ($cards[0] == $cards[1] && $cards[2] == $cards[3] && $cards[3] == $cards[4]);
    }

    protected function isThreeOfAKind(array $cards): bool
    {
        sort($cards);

        return
            ($cards[0] == $cards[1] && $cards[1] == $cards[2]) ||
            ($cards[1] == $cards[2] && $cards[2] == $cards[3]) ||
            ($cards[2] == $cards[3] && $cards[3] == $cards[4]);
    }

    protected function isTwoPairs(array $cards): bool
    {
        sort($cards);

        return
            ($cards[0] == $cards[1] && $cards[2] == $cards[3]) ||
            ($cards[0] == $cards[1] && $cards[3] == $cards[4]) ||
            ($cards[1] == $cards[2] && $cards[3] == $cards[4]);
    }

    protected function isOnePair(array $cards): bool
    {
        return count(array_unique($cards)) == count($cards) - 1;
    }


    protected function sortByStrengthAndFirstCardFromLeft(array $hand, array $otherHand): int
    {
        if ($hand['strength'] == $otherHand['strength']) {
            return $this->sortByCardStrengthFromTheLeft($hand, $otherHand);
        }

        return $hand['strength'] > $otherHand['strength'] ? 1 : -1;
    }

    protected function sortByCardStrengthFromTheLeft(array $hand, array $otherHand): int
    {
        for ($i = 0; $i < count($hand['cards']); $i++) {
            if ($hand['cards'][$i] == $otherHand['cards'][$i]) {
                continue;
            }

            return $hand['cards'][$i] > $otherHand['cards'][$i] ? 1 : -1;
        }

        return 0;
    }
}


