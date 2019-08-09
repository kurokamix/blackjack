<?php

require_once(dirname(__FILE__) . "/card.php");
require_once(dirname(__FILE__) . "/deck.php");

class BlackjackPlayer
{
    public $name;
    public $deck;
    public $cards;

    const MAX_SCORE = 21;

    public function __construct($name, $deck, $cards)
    {
        $this->name  = $name;
        $this->deck  = $deck;
        $this->cards = $cards;
    }

    function _score()
    {
        $score = 0;

        foreach ($this->cards as $card) {
            $score += $card->point();
        };

        return $score;
    }

    function score()
    {
        $score = $this->_score();

        // NOTE: Aceのスコア計算処理。 Study用なのでScore関連のクラスは作成しない。
        if ($score > Dealer::MAX_SCORE) {
            foreach ($this->cards as $card) {
                if ($card->isAce()) {
                    $score -= 10; // NOTE: ACEのスコアは 1 or 11。21を超えていた場合は10を引くことで1として扱う。
                }
                if ($score <= Dealer::MAX_SCORE) {
                    break;
                }
            };
        }

        return $score;
    }

    function isScoreBurst()
    {
        return $this->score() > BlackjackPlayer::MAX_SCORE;
    }

    function isScoreBlackJack()
    {
        return $this->score() == BlackjackPlayer::MAX_SCORE;
    }

    function draw()
    {
        array_push($this->cards, $this->deck->draw());
    }

    function canDraw()
    {
        return true;
    }

    function toString()
    {
        $str = $this->name;
        $str = $str . "　[" . (string) $this->score() . "]　";

        $str = $str . "{";
        foreach ($this->cards as $card) {
            $str = $str . " " . $card->toString() . " ";
        };
        $str = $str . "}";

        return $str;
    }
}


class Dealer extends BlackjackPlayer
{
    const DEFAULT_NAME = "ディーラー";
    const STAND_SCORE = 17;

    public static function New($deck)
    {
        return new Dealer(Dealer::DEFAULT_NAME, $deck, []);
    }

    function canDraw()
    {
        return $this->score() < Dealer::STAND_SCORE;
    }
}

class Player extends BlackjackPlayer
{
    public static function New($name, $deck)
    {
        return new Player($name, $deck, []);
    }

    public static function NewSTDIN($deck)
    {
        echo ("プレイヤーの名前を入力して下さい: ");
        return Player::New(str_replace(array("\r\n", "\r", "\n"), "", fgets(STDIN, 4096)), $deck, []);
    }

    function canDraw()
    {
        while (true) {
            if ($this->isScoreBurst()) {
                return false;
            }
            if ($this->isScoreBlackJack()) {
                return false;
            }

            echo ("\n");
            echo ("カードを引きますか？？\n");
            echo ("yes/no を入力してください \n");

            $input = fgets(STDIN, 4096);
            if ($input == "y\n" || $input == "yes\n") {
                return true;
            }
            if ($input == "n\n" || $input == "no\n") {
                return false;
            }
        }
    }
}
