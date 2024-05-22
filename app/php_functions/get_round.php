<?php
// This function gets current round number

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT CurrentRound FROM Dilemma WHERE GameId = '{$game_id}'";
$round = mysqli_query($link, $sql_query)->fetch_row()[0];

echo $round;
?>