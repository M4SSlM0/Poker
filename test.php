<?php
    include "functions.php";

    $banco = array( // carte del banco
        new Card(2, 3),
        new Card(2, 7),
        new Card(2, 11),
        new Card(1, 7),
        new Card(2, 7)
    );

    //echo "banco:";
    //var_dump($banco);

    $hand = array(  //mano del giocatore
        new Card(0, 3),
        new Card(3, 14)
    );
    
    //echo "hand:";
    //var_dump($hand);

    //echo "cards:";
    //var_dump($cards);

    $result = evaluate($banco, $hand);     //magia
                                    //rstituisce una classe contentente:
    var_dump($result->result);      //-una stringa con il risulatto della ricerca ("poker", "full house", "pair", ...)
    echo "<br>";
    var_dump($result->value);       //-un intero per il confronto che rappersenta il risultato ottenuto


    //var_dump($cards);

?>