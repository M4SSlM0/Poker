<?php

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "poker";
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    include "./functions.php";
    session_start();
    $deck = $_SESSION["deck"];
    $bet = $_POST["bet"];
    $_SESSION["cash"] = $_SESSION["cash"] - $bet;
?>
<div class="table-cards-container row">
    <?php
        $botsBet = 100;
        $table = $_SESSION["table"];
    ?>
    <div class="card table-card-1">
        <img class="card-image" src="<?= cardToPath($table[0]) ?>" alt="">
    </div>
    <div class="card table-card-2">
        <img class="card-image" src="<?= cardToPath($table[1]) ?>" alt="">
    </div>
    <div class="card table-card-3">
        <img class="card-image" src="<?= cardToPath($table[2]) ?>" alt="">
    </div>
    <div class="card table-card-4">
        <img class="card-image" src="<?= cardToPath($table[3]) ?>" alt="">
    </div>
    <div class="card table-card-5">
        <img class="card-image" src="<?= cardToPath($table[4]) ?>" alt="">
    </div>
</div>
<div class="player-container player-1 center column">
    <?php
        $player1 = $_SESSION["player1"];
    ?>
    <div class="player-name">
        Player 1
    </div>
    <div class="row">
        <div class="card player-card-1">
            <img class="card-image" src="<?= cardToPath($player1[0]) ?>" alt="">
        </div>
        <div class="card player-card-2">
            <img class="card-image" src="<?= cardToPath($player1[1]) ?>" alt="">
        </div>
    </div>
    <div class="player-result">
        <?php
        $result1 = evaluate($table, $player1);
        $result1->player = 1;
        echo $result1->result;
        //echo $result1->value;
        $bet1 = $botsBet;
        ?>
    </div>
</div>
<div class="player-container player-2 center column">
    <?php
        $player2 = $_SESSION["player2"];
    ?>
    <div class="player-name">
        You
    </div>
    <div class="row">
        <div class="card player-card-1">
            <img class="card-image" src="<?= cardToPath($player2[0]) ?>" alt="">
        </div>
        <div class="card player-card-2">
            <img class="card-image" src="<?= cardToPath($player2[1]) ?>" alt="">
        </div>
    </div>
    <div class="player-result">
        <?php
        $result2 = evaluate($table, $player2);
        $result2->player = 2;
        echo $result2->result;
        //echo $result2->value;
        $bet2 = 100;
        ?>
    </div>
</div>
<div class="player-container player-3 center column">
    <?php
        $player3 = $_SESSION["player3"];
    ?>
    <div class="player-name">
        Player 3
    </div>
    <div class="row">
        <div class="card player-card-1">
            <img class="card-image" src="<?= cardToPath($player3[0]) ?>" alt="">
        </div>
        <div class="card player-card-2">
            <img class="card-image" src="<?= cardToPath($player3[1]) ?>" alt="">
        </div>
    </div>
    <div class="player-result">
        <?php
        $result3 = evaluate($table, $player3);
        $result3->player = 3;
        echo $result3->result;
        //echo $result3->value;
        $bet3 = $botsBet;
        ?>
    </div>
</div>
<div class="player-container player-4 center column">
    <?php
        $player4 = $_SESSION["player4"];
    ?>
    <div class="player-name">
        Player 4
    </div>
    <div class="row">
        <div class="card player-card-1">
            <img class="card-image" src="<?= cardToPath($player4[0]) ?>" alt="">
        </div>
        <div class="card player-card-2">
            <img class="card-image" src="<?= cardToPath($player4[1]) ?>" alt="">
        </div>
    </div>
    <div class="player-result">
        <?php
        $result4 = evaluate($table, $player4);
        $result4->player = 4;
        echo $result4->result;
        //echo $result4->value;
        $bet4 = $botsBet;
        ?>
    </div>
</div>
<div class="table-deck card">
    <img class="card-image" src="./cards/back_<?= end($deck)->color == 0 ? "r" : "b" ?>.svg" alt="">
</div>
<?php
    $_SESSION["deck"] = $deck;
?>
<div class="winner center">
    <?php
        $winners = getWinners($result1, $result2, $result3, $result4);
        if(count($winners) == 1)
        if($winners[0]->player==2) echo "You won";
        else echo "Player ".$winners[0]->player." wins";
        else {
        echo "Players ";
        foreach ($winners as $winner) {
            echo ($winner->player == 2 ? "You" : $winner->player) ." ";
        }
        echo "won";
        }
        $isWin = FALSE;
        foreach ($winners as $winner) {
        if($winner->player == 2) $isWin = TRUE;
        }
        $win = 0;
        if($isWin) echo " +".$win = ceil(($botsBet * 3 + $bet) / count($winners));
        $_SESSION["cash"] = $_SESSION["cash"] + $win;

        $query = "UPDATE users SET Cash='".$_SESSION["cash"]."' WHERE Id='".$_SESSION["userId"]."'";
        //var_dump($query);
        $conn->query($query);
    ?>
</div>
<div class="bet-container">
    <div class="chip-border chip1 center">
        <div class="chip-center center">5</div>
    </div>
    <div class="chip-border chip2 center">
        <div class="chip-center center">25</div>
    </div>
    <div class="chip-border chip3 center">
        <div class="chip-center center">10</div>
    </div>
    <div class="click-to-bet">&nbsp;</div>
    <div class="cash-ammount"><?= $_SESSION["cash"] ?></div>
</div>
<form action="">
    <button class="continue-button center" type="submit">Continue playing</button>
</form>