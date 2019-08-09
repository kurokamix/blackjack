<?php

class Card
{
    public $mark;
    public $number;

    public function __construct($mark, $number)
    {
        $this->mark = $mark;
        $this->number = $number;
    }

    public static function NewJoker()
    {
        // TODO: Changing to more better. For example, const or var or etc.
        return new Card("JOKER", "");
    }

    function point()
    {
        if ($this->isAce()) {
            return 11;
        }
        if ($this->isPicture()) {
            return 10;
        }
        if ($this->isJoker()) {
            return 0;
        }

        return (int) $this->number;
    }

    function isAce()
    {
        return in_array($this->number, [Number::ACE]);
    }

    function isPicture()
    {
        return in_array($this->number, [Number::JACK, Number::QUEEN, Number::KING]);
    }

    function isJoker()
    {
        return $this->mark === "JOKER" and $this->number === "";
    }

    function toString()
    {
        return $this->mark . $this->number;
    }
}


class Mark
{
    const HEART    = "❤";
    const CLUB     = "♣";
    const DIAMOND  = "◆";
    const SPADE    = "♠";
}


class Number
{
    const ACE    = "A";
    const TWO    = "2";
    const THREE  = "3";
    const FOUR   = "4";
    const FIVE   = "5";
    const SIX    = "6";
    const SEVEN  = "7";
    const EIGHT  = "8";
    const NINE   = "9";
    const TEN    = "10";
    const JACK   = "J";
    const QUEEN  = "Q";
    const KING   = "K";
}


const MARKS = [
    Mark::HEART,
    Mark::CLUB,
    Mark::DIAMOND,
    Mark::SPADE,
];

const NUMBERS = [
    Number::ACE,
    Number::TWO,
    Number::THREE,
    Number::FOUR,
    Number::FIVE,
    Number::SIX,
    Number::SEVEN,
    Number::EIGHT,
    Number::NINE,
    Number::TEN,
    Number::JACK,
    Number::QUEEN,
    Number::KING,
];
