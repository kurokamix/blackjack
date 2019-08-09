<?php

require_once(dirname(__FILE__) . "/card.php");
require_once(dirname(__FILE__) . "/deck.php");
require_once(dirname(__FILE__) . "/player.php");


function main()
{
    echo ("\n--------------- START ---------------\n");
    sleep(3);

    // NOTE: デックの作成
    $deck = Deck::CreateDeck52();
    $deck->shuffle();

    // NOTE: ブラックジャック ゲーム参加者の作成
    $dealer = Dealer::New($deck);

    echo ("\n   --- プレイヤーを追加しましょう ---   \n");
    sleep(3);
    echo ("\n");

    $players = [];
    array_push($players, Player::NewSTDIN($deck));

    while (true) {
        sleep(3);
        echo ("\n");
        echo ("もう1人追加しますか ？？ \n");
        echo ("yes/no を入力してください \n");

        $input = fgets(STDIN, 4096);
        echo ("\n");
        if ($input == "y\n" || $input == "yes\n") {
            sleep(3);
            array_push($players, Player::NewSTDIN($deck));
        }
        if ($input == "n\n" || $input == "no\n") {
            break;
        }
    }

    $blackjackPlayers = array_merge([$dealer], $players);

    // NOTE: 最初に2枚ずつカードを取る。
    // TODO: めんどくさいので2回呼ぶ方式で良い。
    echo ("\n   --- カードを配ります ---   \n");
    sleep(3);

    foreach ($blackjackPlayers as $bp) {
        $bp->draw();
        $bp->draw();
    }

    // NOTE: 手札を表示する。
    foreach ($blackjackPlayers as $bp) {
        echo ($bp->toString());
        echo ("\n");
    }

    // NOTE: プレイヤー毎にカードを引いていく
    echo ("\n   --- ゲームを開始します ---   \n");
    sleep(3);
    foreach ($blackjackPlayers as $bp) {
        echo ("\n   --- " . $bp->name . "の番です ---   \n");
        echo ($bp->toString());
        echo ("\n");
        sleep(3);

        while ($bp->canDraw()) {
            echo ("\n   --- " . $bp->name . "がカードを引きます ---   \n");

            $bp->draw();
            sleep(3);

            echo ($bp->toString());
            echo ("\n");
        }

        echo ("\n   --- " . $bp->name . "の番が終わりました ---   \n");
        sleep(3);
    }

    echo ("\n   --- 全員の番が終わりました ---   \n");
    sleep(3);

    // NOTE: 結果を計算&表示する
    echo ("\n------------------\n");
    echo ("   結果発表  　");
    echo ("\n------------------\n");
    sleep(3);

    echo ($dealer->toString());
    echo ($dealer->isScoreBlackJack() ? "Blackjack" : "");
    echo ($dealer->isScoreBurst() ? " Burst" : "");
    echo ("\n");

    echo ("\n--- Player ---\n");
    foreach ($players as $p) {
        // プレイヤーがBlackJackした場合はラベルをつける
        echo ($p->isScoreBlackJack() ? "! Blackjack ! " : "");
        // プレイヤーだけがBlackJackした場合は勝利のお祝いメッセージ
        echo ($p->isScoreBlackJack() && !$dealer->isScoreBlackJack() ? "おめでとう " : "");
        // プレイヤーがBurstした場合は必ず負け
        echo ($p->isScoreBurst() ? " [Burst] 残念 " : "");
        // ディーラーだけがBurstした場合は勝ち
        echo (!$p->isScoreBurst() && $dealer->isScoreBurst()  ? "ラッキー! 勝ち " : "");

        // Burstしていない場合はスコアで勝負
        echo (!$p->isScoreBurst() && !$dealer->isScoreBurst() && $p->score() > $dealer->score()  ? "勝ち " : "");
        echo (!$p->isScoreBurst() && !$dealer->isScoreBurst() && $p->score() < $dealer->score()  ? "負け " : "");
        echo (!$p->isScoreBurst() && !$dealer->isScoreBurst() && $p->score() == $dealer->score() ? "同点 " : "");

        // BlackJackかつ同点の場合のみ、カード枚数で再判定 → このルールいる？？笑
        /* 手札が見えている状態でこのルールがあると糞つまらないのでコメントアウト
        echo ($p->isScoreBlackJack() && $dealer->isScoreBlackJack() && count($p->cards) < count($dealer->cards)    ? "だが、勝ちだ " : "");
        echo ($p->isScoreBlackJack() && $dealer->isScoreBlackJack() && count($p->cards) > count($dealer->cards)    ? "だが、負けだ " : "");
        echo ($p->isScoreBlackJack() && $dealer->isScoreBlackJack() && count($p->cards) === count($dealer->cards)  ? "引き分けだ.. " : "");
        */

        // 手札も表示
        echo (": ");
        echo ($p->toString());
        echo ("\n");
    }

    echo ("\n---------------  END  ---------------\n");
}

main();
