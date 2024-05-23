<?php
// This function gets current round number. Returns 'finished' if the game is finished as an edge case

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT CurrentRound, GamePhase FROM Dilemma WHERE GameId = '{$game_id}'";
$res = mysqli_query($link, $sql_query)->fetch_object();
if ($res->GamePhase == 'Finished') {
    echo 'finished';
    exit;
}

echo $res->CurrentRound;
?>