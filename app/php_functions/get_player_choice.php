<?php
// This function gets the player's choice in the current round (returns 'finished' when the game is finished as an edge case)

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT Status_Player{$player_num} AS Status_Player, GamePhase FROM Dilemma WHERE GameId = '{$game_id}'";
$res = mysqli_query($link, $sql_query)->fetch_object();

if ($res->GamePhase == 'Finished') {
    echo 'finished';
    exit;
}

// Return player's choice:
switch ($res->Status_Player) {
    case 0: // if didn't choose yet
        echo 'unknown';
        exit;
    case 1: // if betrayed
        echo 'betrayed';
        exit;
    case 2: // if cooperated
        echo 'cooperated';
        exit;
}


?>