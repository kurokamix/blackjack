# coding: utf-8

from collections import deque
import random


class cardgame:
    def __init__(self):
        self.numberlist = range(52)
        self.deque_numberlist = deque(self.numberlist)
        self.card = []
        self.player = []
        self.playercard = {}

    def play(self):
        i = int(raw_input("何人で遊びますか？ :"))
        j = int(raw_input("カードを何枚ずつ引きますか？ :"))
        hasdealer = raw_input("ディーラは必要ですか？？ 【必要:y いらない:n】：")
        while hasdealer != "y" and hasdealer != "n":
                hasdealer = raw_input("y または　n　を入力してください:")
        if hasdealer == 'y':
            hasdealer = 1
        else:
            hasdealer = 0

        while (i + hasdealer) * j > len(self.deque_numberlist):
            print "【　エラー : トランプの枚数が足りません。　】"
            print "もう一度、プレイヤー数と、配る枚数を入力して下さい。"
            i = int(raw_input("何人で遊びますか？ :"))
            j = int(raw_input("カードを何枚ずつ引きますか？ :"))
            hasdealer = raw_input("ディーラは必要ですか？？ 【必要:y いらない:n】：")
            while hasdealer != "y" and hasdealer != "n":
                hasdealer = raw_input("y または　n　を入力してください:")
            if hasdealer == 'y':
                hasdealer = 1
            else:
                hasdealer = 0

        for x in range(i):
            player = raw_input('プレイヤー' + str(x + 1) + 'の名前を入力して下さい :')
            while player in self.player:
                player = raw_input('【　プレイヤー名重複！もう一度、入力して下さい　】 :')
            self.player.append(player)

        if hasdealer == 1:
            self.player.append("ディーラー")

        #カードリストを初期化して、カードを配る
        self.card = range(j)
        self.cardread()

        #ブラックジャック
        game = self.blackjack
        game()

        retry = "y"
        while retry == "y":
                retry = raw_input("☆もう一度プレイしますか？　[ y / n ]:")
                while retry != "y" and retry != "n":
                    retry = raw_input("y または　n　を入力してください:")
                if retry == "y":
                    self.cardread()
                    game()

    def cardread(self):
        self.playercard = {}
        self.shuffle()

        for player in self.player:
            for x in range(len(self.card)):
                val = self.deque_numberlist.popleft()
                mark = self.mark(int(val))
                number = self.number(int(val))
                self.card[x] = mark + str(number)
            self.playercard[player] = self.card[:]

    def drow(self, dictname, player):
        val = self.deque_numberlist.popleft()
        mark = self.mark(int(val))
        number = self.number(int(val))
        dictname[player].append(mark + number)

    def blackjack(self):
        print ''
        print '----ブラックジャックスタート----'
        print ''
        print '【ディーラーの手札】※一枚のみ公開 '
        print self.playercard["ディーラー"][0]
        print '備考：ディーラーは一枚のみ手札を公開し、一番最後にカードを引きます'

        total = self.blackjack_cardtotal()
        for player in self.player:
            retry = "y"
            messege_flg = 0
            while retry == "y":
                print ''
                if messege_flg == 0:
                    print '----------------------------------'
                    print '【　' + player + ' さん】'
                    print player + 'さんの番です。'

                if total[player] == 21:
                    print '【手札】'
                    for i in range(len(self.playercard[player])):
                        print self.playercard[player][i],
                    print ''
                    print '現在のカード合計値は【　' + str(total[player]) + '　】です'
                    print '【　！！！　ブラックジャック　！！！　】'
                    total[player] = 'ブラックジャック'
                    if player == 'ディーラー':
                        raw_input("ゲーム終了です。Enterを押して下さい。")
                    else:
                        print 'おめでとうございます！'
                        raw_input("Enterを押して下さい。次のプレイヤーへ交代です")
                    break

                elif total[player] > 21:
                    print '【手札】'
                    for i in range(len(self.playercard[player])):
                        print self.playercard[player][i],
                    print ''
                    print '現在のカード合計値は【　' + str(total[player]) + '　】です'
                    print '【　負け　】 カード合計値が、21をオーバーしました。　'
                    if player == 'ディーラー':
                        raw_input("ゲーム終了です。Enterを押して下さい。")
                    else:
                        print '残念！　ドボン！！'
                        raw_input("Enterを押して下さい。次のプレイヤーへ交代です")
                    total[player] = '21オーバー'
                    break

                print '【手札】'
                for i in range(len(self.playercard[player])):
                    print self.playercard[player][i],
                print ''

                if player == 'ディーラー':
                    if total[player] < 17:
                        retry = 'y'
                        print '現在のカード合計値は【　' + str(total[player]) + '　】です'
                        print '【ディーラーが、カードをもう一枚引きます。】'
                        raw_input("Enterを押して下さい。")
                    else:
                        retry = 'n'
                        print '現在のカード合計値は【　' + str(total[player]) + '　】です'
                        print '【ディーラーが、STOPをかけました。ゲーム終了です。】'
                        raw_input("Enterを押して下さい")
                else:
                    print '現在のカード合計値は【　' + str(total[player]) + '　】です'
                    retry = raw_input("もう一枚引く場合は y を、引かない場合は n を入力してください:")
                    while retry != "y" and retry != "n":
                        retry = raw_input("y または　n　を入力してください:")

                if retry == "y":
                    self.drow(self.playercard, player)
                    player_total = self.blackjack_cardtotal()
                    total[player] = player_total[player]
                elif retry == "n":
                    total[player] = str(total[player])

                messege_flg = 1

        dealerpoint = total[player]
        print ''
        print "-----------------"
        print "☆☆☆対戦結果☆☆☆"
        print 'ディーラー:' + total[player]
        for player in self.player:
                if player == 'ディーラー':
                    break
                if dealerpoint < total[player] and total[player] != '21オーバー':
                    result = '(勝ち)'
                elif dealerpoint == '21オーバー' and total[player] != '21オーバー':
                    result = '(勝ち)'
                elif dealerpoint > total[player] or total[player] == '21オーバー':
                    result = '(負け)'
                else:
                    result = '(引き分け)'
                print player + ':' + total[player] + result
        print "-----------------"

    def mark(self, val):
            if val <= 12:
                mark = '♥'
            elif val <= 25:
                mark = '♣'
            elif val <= 38:
                mark = '♦'
            elif val <= 51:
                mark = '♠'
            else:
                return 'ジョーカー'

            return mark

    def number(self, val):
        numlist = [range(0, 13), range(13, 26), range(26, 39), range(39, 52)]

        for i in range(1, 14):
            numrow = [row[i - 1] for row in numlist]

            if val in numrow:
                if i == 1:
                    i = 'A'
                elif i == 11:
                    i = 'J'
                elif i == 12:
                    i = 'Q'
                elif i == 13:
                    i = 'K'
                else:
                    i = str(i)
                return i
        return ''

    def blackjack_cardtotal(self):
        playercard_backup = {}

        for player in self.player:
            playercard_backup[player] = self.playercard[player][:]
            index = 0
            for playercard_list in self.playercard[player]:
                if playercard_list[-1] == 'J':
                    self.playercard[player][index] = '10'
                elif playercard_list[-1] == 'Q':
                    self.playercard[player][index] = '10'
                elif playercard_list[-1] == 'K':
                    self.playercard[player][index] = '10'
                elif playercard_list[-1] == 'A':
                    self.playercard[player][index] = '11'

                index += 1

        blackjack_total = self.cardtotal()

        for player in self.player:
            if '11' in self.playercard[player]:
                Ace = self.playercard[player].count('11')
                while Ace > 0 and blackjack_total[player] > 21:
                    blackjack_total[player] -= 10
                    Ace -= 1
            self.playercard[player] = playercard_backup[player][:]

        return blackjack_total

    def cardtotal(self):
        total = {}

        for player in self.player:
            total[player] = 0
            playercard_list = self.playercard[player]
            for card in playercard_list:
                if card[-1] == 'A':
                    cardvalue = '1'
                elif card[-1] == 'J':
                    cardvalue = '11'
                elif card[-1] == 'Q':
                    cardvalue = '12'
                elif card[-1] == 'K':
                    cardvalue = '13'
                elif card[-2].isdigit():
                    cardvalue = card[-2:]
                else:
                    cardvalue = card[-1]
                total[player] += int(cardvalue)

        return total

    def shuffle(self):
        shuffle_count = random.randint(3, 5)

        for hoge in range(shuffle_count):
            self.shuffle2()
            self.shuffle1()

        self.deque_numberlist = deque(self.numberlist)

    def shuffle1(self):
        halfdeck1 = deque(self.numberlist[:len(self.numberlist) / 2])
        halfdeck2 = deque(self.numberlist[len(self.numberlist) / 2:])
        shuffledeck = []

        for hoge in range(len(self.numberlist) / 2):
            a = halfdeck1.popleft()
            b = halfdeck2.popleft()
            shuffledeck.append(a)
            shuffledeck.append(b)

        self.numberlist = shuffledeck

    def shuffle2(self):
        shuffledeck = self.numberlist[:]
        r1 = random.randint(30, 50)
        length = len(shuffledeck) - 1

        for hoge in range(r1):
            r2 = random.randint(1, length)
            r3 = random.randint(r2, length)
            a = shuffledeck[r2:r3]
            b = shuffledeck[:r2]
            shuffledeck[:(r3 - r2)] = a
            shuffledeck[(r3 - r2):(r3 - r2) + len(b)] = b

        self.numberlist = shuffledeck


if __name__ == '__main__':
    import blackjack
    cg = blackjack.cardgame()
    cg.play()
