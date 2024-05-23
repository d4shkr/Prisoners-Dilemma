<?php
// This function gets current round number. Returns 'finished' if the game is finished as an edge case

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT CurrentRound, GamePhase, MaxRounds, MaxRoundsKnown FROM Dilemma WHERE GameId = '{$game_id}'";
$res = mysqli_query($link, $sql_query)->fetch_object();
if ($res->GamePhase == 'Finished') {
    echo 'finished';
    exit;
}

// If we show total number of rounds in the game:
if ($res->MaxRoundsKnown) {
    echo $res->CurrentRound . " / " . $res->MaxRounds;
// If we don't:
} else {
    echo $res->CurrentRound;
}

?>