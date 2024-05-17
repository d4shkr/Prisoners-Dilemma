<?php
include("get_game_id.php");

// Player chose to cooperate, so we need to set Status_Player to 1
$sql_query = "UPDATE Dilemma SET Status_Player{$player_num} = 1 WHERE GameId = '{$game_id}'";
mysqli_query($link, $sql_query)
?>