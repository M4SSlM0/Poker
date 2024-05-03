<?php

    class Card{
        public int $suit;
        public int $value;
        public int $color;
        public function __construct(int $suit, int $value, int $color = 0) {
            $this->suit = $suit;
            $this->value = $value;
            $this->color = $color;
        }
    }

    class CheckResult{
        public string $result;
        public int $value;
        public int $player;
        public function __construct(string $result, int $value1, int $value2 = null, int $value3 = null, int $value4 = null, int $value5 = null){
            $this->result = $result;
            switch($result){
                case "flush five":
                    $this->value = 1300 + $value1;
                    break;
                case "poker flush":
                    $this->value = 1200 + $value1;
                    break;
                case "five of a kind":
                    $this->value = 1100 + $value1;
                    break;
                case "straight flush":
                    if($value1 != 14)
                        $this->value = 1000 + $value1 + 4;
                    else $this->value = 1000 + 5;
                    break;
                case "flush house":
                    $this->value = 900 + (3*$value1) + (2*$value2);
                    break;
                case "poker":
                    $this->value = 800 + $value1;
                    break;
                case "full house":
                    $this->value = 700 + (3*$value1) + (2*$value2);
                    break;
                case "flush":
                    $this->value = 600 + $value1 + $value2 + $value3 + $value4 + $value5;
                    break;
                case "straight":
                    if($value1 != 14)
                        $this->value = 500 + $value1 + 4;
                    else $this->value = 500 + 5;
                    break;
                case "three of a kind":
                    $this->value = 400 + $value1;
                    break;
                case "two pair":
                    $this->value = 300 + $value1 + $value2;
                    break;
                case "pair":
                    $this->value = 200 + $value1;
                    break;
                case "high card":
                    $this->value = 100 + $value1;
                    break;
            }
        }
    }

    function cmp ($a, $b){
        if ($a->value == $b->value){
            if($a->suit == $b->suit) return 0;
            return ($a->suit < $b->suit) ? -1 : 1;
        }
        return ($a->value < $b->value) ? -1 : 1;
    }

    function newDeck(int $packs){
        $deck = array();

        $temp = $packs / 2;

        for ($i=0; $i < $temp; $i++) { 
            for ($j=2; $j < 15; $j++) { 
                for ($k=0; $k < 4; $k++) { 
                    array_push($deck, new Card($k, $j, 0));
                    array_push($deck, new Card($k, $j, 1));
                }
            }
        }

        if($packs % 2 != 0){
            for ($j=2; $j < 15; $j++) { 
                for ($k=0; $k < 4; $k++) { 
                    array_push($deck, new Card($k, $j));
                }
            }
        }

        shuffle($deck);

        return $deck;
    }

    function cardToPath(Card $card){
        $basePath = "./cards/";
        $cardPath = "";
        $extension = ".svg";
        switch ($card->suit) {
            case '0':
                $cardPath = "Clubs";
                break;
            case '1':
                $cardPath = "Diamonds";
                break;
            case '2':
                $cardPath = "Hearts";
                break;
            case '3':
                $cardPath = "Spades";
                break;
        }
        
        return $basePath.$card->value.$cardPath.$extension;
    }

    function getWinners($player1, $player2, $player3, $player4){
        $winners = array($player1);
        if($player2->value > $winners[0]->value) $winners = array($player2);
        else if($player2->value == $winners[0]->value) array_push($winners, $player2);
        if($player3->value > $winners[0]->value) $winners = array($player3);
        else if($player3->value == $winners[0]->value) array_push($winners, $player3);
        if($player4->value > $winners[0]->value) $winners = array($player4);
        else if($player4->value == $winners[0]->value) array_push($winners, $player4);
        return $winners;
    }

    //----------------------------------------------------------------------------------------------------------------------------
    //---------------------------fix--------------------------------------

    function evaluate($table, $player){

        $cards = array_merge($table, $player);

        usort($cards, "cmp");

        $cards = array_reverse($cards);

        //var_dump($cards);

        $result;

        if($result = checkFiveFlush($cards)) return $result;
        else if($result = checkPokerFlush($cards)) return $result;
        else if($result = checkFiveOfAKind($cards)) return $result;
        else if($result = checkStraightFlush($cards)) return $result;
        else if($result = checkFlushHouse($cards)) return $result;
        else if($result = checkPoker($cards)) return $result;
        else if($result = checkFullHouse($cards)) return $result;
        else if($result = checkFlush($cards)) return $result;
        else if($result = checkStraight($cards)) return $result;
        else if($result = checkThree($cards)) return $result;
        else if($result = checkTwoPair($cards)) return $result;
        else if($result = checkPair($cards)) return $result;
        else if($result = checkHigh($cards)) return $result;

    }

    function countSuits($cards, $suit){
        $count = 0;
        for ($i=0; $i < count($cards); $i++) { 
            if($cards[$i]->suit == $suit) $count++;
        }
        return $count;
    }

    function checkFlush3($cards){
        $count = 0;
        $positions = array(0);
        for ($i=0; $i < count($cards) - 1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value){
                $count++;
                array_push($positions, $i + 1);
            }
            else{
                if($count==2) 
                    if($positions = checkFLushHouse3($cards, $positions)) return $positions;
                $count = 0;
                $positions = array($i);
            }
            if($count == count($cards) - 1)
                 if($positions = checkFLushHouse3($cards, $positions)) return $positions;
            //var_dump($count);
            //var_dump($positions);
            //echo "<br>";

        }
    }

    function checkFlushHouse3($cards, $positions){
        $count = 0;
        $positions3 = array($positions[0]);
        $suit = null;
        for ($i=0; $i < count($positions) - 1; $i++) { 
            if($suit == null && countSuits($cards, $cards[$positions[$i]]->suit) >= 3) $suit = $cards[$positions[$i]]->suit;
            if($suit != null){
                //var_dump($positions);
                if($cards[$positions[$i]]->value == $cards[$positions[$i + 1]]->value && $cards[$positions[$i]]->suit == $suit && $cards[$positions[$i + 1]]->suit == $suit){
                    $count++;
                    array_push($positions3, $positions[$i + 1]);
                } 
                else {
                    $count = 0;
                    $positions3 = array($positions[$i]);
                }

                //var_dump($cards[$positions[$i]]);
                //var_dump($count);
                //echo "<br>";

                if($count == 2) return $positions3;
                
            }

        }
    }

    function checkFlush2($cards, $suit){
        //var_dump($cards);
        $count = 0;
        $positions = array(0);
        //var_dump($cards);
        for ($i=0; $i < count($cards) - 1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value){
                $count++;
                array_push($positions, $i + 1);
            }
            else{
                if($count==1) 
                    if($positions = checkFlushHouse2($cards, $positions, $suit)) return $positions;
                $count = 0;
                $positions = array($i);
            }
            if($count == count($cards) - 1)
                if($positions = checkFlushHouse2($cards, $positions, $suit)) return $positions;

            //var_dump($count);
            //var_dump($positions);
            //echo "<br>";

        }
    }

    function checkFlushHouse2($cards, $positions, $suit){
        $count = 0;
        $positions2 = array($positions[0]);
        $suit = $suit;
        //var_dump($cards);
        //echo "<br>";
        //var_dump($positions);
        //echo "<br>";
        //var_dump($suit);
        for ($i=0; $i < count($positions) - 1; $i++) { 
            if($cards[$positions[$i]]->value == $cards[$positions[$i + 1]]->value && $cards[$positions[$i]]->suit == $suit && $cards[$positions[$i + 1]]->suit == $suit){
                $count++;
                array_push($positions2, $positions[$i + 1]);
            } 
            else {
                $count = 0;
                $positions2 = array($positions[$i]);
            }

            //var_dump($cards[$positions[$i]]);
            //var_dump($count);
            //echo "<br>";

            if($count == 1) return $positions2;

        }
    }

    function checkFiveFlush($cards){
        $count = 0;
        $positions = array(0);
        for ($i=0; $i < count($cards) - 1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value && $cards[$i]->suit == $cards[$i + 1]->suit) $count++;
            else $count = 0;

            if($count == 4) return new CheckResult("flush five", $cards[$i + 1]->value);
        }
    }

    function checkFiveOfAKind($cards){
        $count = 0;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value) $count++;
            else $count = 0;
            if($count == 4) return new CheckResult("five of a kind", $cards[$i + 1]->value);
        }
    }

    function checkPokerFlush($cards){
        $count = 0;
        $positions = array(0);
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value && $cards[$i]->suit == $cards[$i + 1]->suit) $count++;
            else $count = 0;

            //var_dump($count);

            if($count == 3) return new CheckResult("poker flush", $cards[$i + 1]->value);
        }
    }

    function checkStraightFlush($cards){
        $count = 0;
        $suit = null;
        $lastCard;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($suit == null && countSuits($cards, $cards[$i]->suit) >= 5) $suit = $cards[$i]->suit;
            if($suit != null){
                if($cards[$i + 1]->suit == $suit && $cards[$i]->value == $cards[$i + 1]->value+1){
                    $count++;
                    $lastCard = $i + 1;
                } 
                else if(!($cards[$i]->value == $cards[$i + 1]->value)) $count = 0;

                /*var_dump($cards[$i]->value);
                var_dump($cards[$i + 1]->value);
                echo "<br>";*/
            }

            /*var_dump($count);
            echo "lastCard:";
            var_dump($cards[$lastCard]);
            echo "<br>";*/
            

            if($count == 4) return new CheckResult("straight flush", $cards[$i + 1]->value);
            if($count == 3 && $cards[$lastCard]->value == 2){
                $i = 0;
                while($cards[$i]->value == 14){
                    if($cards[$i]->suit == $suit) return new CheckResult("straight flush", $cards[$i]->value);
                    $i++;
                }
            }
        }
    }

    function checkPoker($cards){
        $count = 0;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value) $count++;
            else $count = 0;

            if($count == 3 ) return new CheckResult("poker", $cards[$i + 1]->value);
        }
    }

    function checkFlushHouse($cards){
        $flush3 = checkFlush3($cards);
        $temp = $cards;
        $temp1 = array();
        if($flush3 != null){
            foreach ($flush3 as $position) {
                //var_dump($position);
                //echo "<br>";
                unset($temp[$position]);
            }
            foreach ($temp as $card) {
                array_push($temp1, $card);
            }
            //var_dump($temp);

            if($flush2 = checkFlush2($temp1, $cards[$flush3[0]]->suit)) return new CheckResult("flush house", $cards[$flush3[0]]->value, $temp1[$flush2[0]]->value);
        }
    }

    function checkFullHouse($cards){
        $three = checkHouseThree($cards);
        $temp = $cards;
        $temp1 = array();
        if($three != null){
            //var_dump($three);
            foreach ($three as $position) {
                //var_dump($cards[$position]);
                //echo "<br>";
                unset($temp[$position]);
            }
            foreach ($temp as $card) {
                array_push($temp1, $card);
            }
            //var_dump($temp);
            if($pair = checkHousePair($temp1)) return new CheckResult("full house", $cards[$three[0]]->value, $temp1[$pair[0]]->value);
        }
    }

    function checkHouseThree($cards){
        //var_dump($cards);
        //echo "<br>";
        $count = 0;
        $positions = array(0);
        for ($i=0; $i < count($cards) - 1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value){
                //var_dump($cards[$i]);
                //echo "<br>";
                //var_dump($cards[$i + 1]);
                //echo "<br>";
                $count++;
                array_push($positions, $i + 1);
            } 
            else{
                $count = 0;
                $positions = array($i + 1);
            } 

            //var_dump($positions);
            //echo "<br>";

            if($count == 2) return $positions;
        }
    }

    function checkHousePair($cards){
        for ($i=0; $i < count($cards) - 1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value) return array($i, $i + 1);
        }
    }

    function checkFlush($cards){
        $count = 0;
        $positions = array(0);
        $suit = null;
        for ($i=0; $i < count($cards); $i++) { 
            if($suit == null && countSuits($cards, $cards[$i]->suit) >=5) $suit = $cards[$i]->suit;
            if($cards[$i]->suit == $suit){
                $count++;
                array_push($positions, $i);
            }

            //var_dump($count);

            if($count == 5) return new CheckResult("flush", $cards[$positions[0]]->value, $cards[$positions[1]]->value, $cards[$positions[2]]->value, $cards[$positions[3]]->value, $cards[$positions[4]]->value);
        }
    }

    function checkStraight($cards){
        $count = 0;
        $lastCard;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value+1){
                $count++;
                $lastCard = $i + 1;
            } 
            else if(!($cards[$i]->value == $cards[$i+ 1]->value)) $count = 0;

            /*var_dump($cards[$i]->value);
            var_dump($cards[$i + 1]->value);
            echo "<br>";*/
            
            if($count == 4) return new CheckResult("straight", $cards[$i + 1]->value);
            if($count == 3 && $cards[$lastCard]->value == 2){
                if($cards[0]->value == 14) return new CheckResult("straight", $cards[0]->value);
            }
        }
    }

    function checkThree($cards){
        $count = 0;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value) $count++;
            else $count = 0;

            if($count == 2) return new CheckResult("three of a kind", $cards[$i + 1]->value);
        }
    }

    function checkTwoPair($cards){
        $pair = checkHousePair($cards);
        //var_dump($pair);
        $temp = $cards;
        $temp1 = array();
        if($pair != null){
            foreach ($pair as $position) {
                //var_dump($position);
                //echo "<br>";
                unset($temp[$position]);
            }
            foreach ($temp as $card) {
                array_push($temp1, $card);
            }
            //var_dump($temp);
            if($flush2 = checkHousePair($temp1)) return new CheckResult("two pair", $cards[$pair[0]]->value, $temp1[$flush2[0]]->value);
        }
    }

    function checkPair($cards){
        $count = 0;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value == $cards[$i + 1]->value) $count++;
            else $count = 0;

            if($count == 1) return new CheckResult("pair", $cards[$i + 1]->value);
        }
    }

    function checkHigh($cards){
        $high = 0;
        for ($i=0; $i < count($cards)-1; $i++) { 
            if($cards[$i]->value > $high) $high = $cards[$i]->value;
        }
        //var_dump($high);
        return new CheckResult("high card", $high);
    }
?>