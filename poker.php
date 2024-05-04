<html>
  <head>
    <link rel="stylesheet" href="./poker.css" />
    <script src="./HTMX/htmx.min.js"></script>
  </head>
  <body>
    <?php

      include "./functions.php";

      session_start();

      $deck;

      if(!isset($_SESSION["deck"]) || count($_SESSION["deck"]) <= 14)
        $deck = newDeck(7);
      else $deck = $_SESSION["deck"];

      //var_dump($deck);

    ?>
    <div class="background">
      <div class="centerw">
        <div class="table-container">
          <div class="table-border centerw">
            <div class="table"></div>
          </div>
        </div>
      </div>
    </div>
    <div id="debug">
      <div class="table-cards-container row">
        <?php
          $botsBet = 100;
          $table = array();
          for ($i=0; $i < 5; $i++) { 
            array_push($table, array_pop($deck));
          }
          $tempTable = array($table[0], $table[1]);
          $_SESSION["table"] = $table;
        ?>
        <div class="card table-card-1">
          <img class="card-image" src="<?= cardToPath($table[0]) ?>" alt="">
        </div>
        <div class="card table-card-2">
          <img class="card-image" src="<?= cardToPath($table[1]) ?>" alt="">
        </div>
        <div class="card table-card-3">
          <img class="card-image" src="<?= "./cards/back_".($table[2]->color == 0 ? "r" : "b").".svg" ?>" alt="">
        </div>
        <div class="card table-card-4">
          <img class="card-image" src="<?= "./cards/back_".($table[3]->color == 0 ? "r" : "b").".svg" ?>" alt="">
        </div>
        <div class="card table-card-5">
          <img class="card-image" src="<?= "./cards/back_".($table[4]->color == 0 ? "r" : "b").".svg" ?>" alt="">
        </div>
      </div>
      <div class="player-container player-1 center column">
        <?php
          $player1 = array();
          array_push($player1, array_pop($deck));
          array_push($player1, array_pop($deck));
          $_SESSION["player1"] = $player1;
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
            $result1 = evaluate($tempTable, $player1);
            $result1->player = 1;
            echo $result1->result;
            //echo $result1->value;
            $bet1 = $botsBet;
          ?>
        </div>
      </div>
      <div class="player-container player-2 center column">
        <?php
          $player2 = array();
          array_push($player2, array_pop($deck));
          array_push($player2, array_pop($deck));
          $_SESSION["player2"] = $player2;
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
            $result2 = evaluate($tempTable, $player2);
            $result2->player = 2;
            echo $result2->result;
            //echo $result2->value;
            $_SESSION["bet"] = 100;
          ?>
        </div>
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
        <div class="click-to-bet">Click to bet</div>
        <div class="cash-ammount" id="cash-ammount"><?= $_SESSION["cash"] ?></div>
      </div>
      <div class="player-container player-3 center column">
        <?php
          $player3 = array();
          array_push($player3, array_pop($deck));
          array_push($player3, array_pop($deck));
          $_SESSION["player3"] = $player3;
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
            $result3 = evaluate($tempTable, $player3);
            $result3->player = 3;
            echo $result3->result;
            //echo $result3->value;
            $bet3 = $botsBet;
          ?>
        </div>
      </div>
      <div class="player-container player-4 center column">
        <?php
          $player4 = array();
          array_push($player4, array_pop($deck));
          array_push($player4, array_pop($deck));
          $_SESSION["player4"] = $player4;
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
            $result4 = evaluate($tempTable, $player4);
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
      <!--
      <div class="winner center">
        <?php/*
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
          if($isWin) echo " +".ceil(($bet1 + $bet2 + $bet3 + $bet4) / count($winners)) - 100;
          else echo " -".$bet2;*/
        ?>
      </div>
        -->
      <div class="custom-modal-background center" id="modal" style="display:none;">
        <div class="custom-modal-outline center">
          <div class="custom-modal column">
            <div class="modal-header">
              <div class="modal-close center" id="modal-close">&times;</div>
            </div>
            <div class="row">
              <div class="modal-body center column">
                <div class="modal-title">Bet ammount:</div>
                  <input class="modal-input" id="bet" type="number" name="bet" value="<?= $_SESSION["cash"] > 100 ? "100" : $_SESSION["cash"] ?>" min="100" step="100" max="<?= $_SESSION["cash"] ?>">
                <button id="submit-bet" class="modal-submit center" hx-post="./reevaluate.php" hx-trigger="click" hx-swap="innerHTML" hx-target="#debug" hx-include="#bet" <?= $_SESSION["cash"] == 0 ? "disabled" : ""?>>Bet</button>
              </div>
              <div class="modal-body center column">
                <div class="modal-title">Add ammount:</div>
                  <input class="modal-input" id="add" type="number" name="add" value="100" min="100" step="100">
                  <div class="modal-submit center" id="submit-add" hx-post="./addCash.php" hx-trigger="click" hx-swap="afterend" hx-target="#debug" hx-include="#add">Add</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
        let betButtons = document.getElementsByClassName("chip-border");
        let modal = document.getElementById("modal");
        let close = document.getElementById("modal-close");
        console.log(betButtons);
        for (let i = 0; i < betButtons.length; i++) {i
          betButtons[i].onclick = ()=>{
            if(modal.style.display == "none") modal.style.display = "flex";
          };
        }
        close.onclick = ()=>{
          if(modal.style.display == "flex") modal.style.display = "none";
        };
      </script>
      <script>
        let input = document.getElementById("bet");
        input.onchange = ()=>{
          if(parseInt(input.value) < parseInt(input.min)) input.value = input.min;
          if(parseInt(input.value) > parseInt(input.max)) input.value = input.max;
        }
      </script>
      <script>
        let add = document.getElementById("add");
        let submitAdd = document.getElementById("submit-add");
        let ammount = document.getElementById("cash-ammount");
        add.onchange = ()=>{
          if(parseInt(add.value) < parseInt(add.min)) add.value = add.min;
        }
        submitAdd.onclick =()=>{
          input.max = parseInt(input.max) + parseInt(add.value);
          ammount.innerText = input.max;
        }
        let submitBet = document.getElementById("submit-bet")
        input.oninput=()=>{
          if(input.value==0)submitBet.disabled=true;
          else submitBet.disabled=false;
        };
      </script>
    </div>
  </body>
</html>
