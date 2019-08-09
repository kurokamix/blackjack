<?php

require_once(dirname(__FILE__) . "/card.php");


class Deck
{
    public $cards;

    public function __construct($cards)
    {
        $this->cards = $cards;
    }

    public static function CreateDeck52()
    {
        $cards = [];

        foreach (MARKS as $mark) {
            foreach (NUMBERS as $number) {
                array_push($cards, new Card($mark, $number));
            };
        };

        return new Deck($cards);
    }

    function CreateDeck54()
    {
        $deck = Deck::CreateDeck52();
        array_push($deck->cards, Card::newJoker());
        array_push($deck->cards, Card::newJoker());
        return $deck;
    }

    function draw()
    {
        return array_shift($this->cards);
    }

    function shuffle()
    {
        shuffle($this->cards);
        return $this;
    }

    function toString()
    {
        // TODO: Maybe, we can changing to also printf of built in function.
        $str = "";

        foreach ($this->cards as $card) {
            $str = $str . $card->toString() . " ";
        };

        return $str;
    }
}
