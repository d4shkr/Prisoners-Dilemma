<?php
include("get_game_id.php");

// Player chose to cooperate, so we need to set Status_Player to 2
$sql_query = "UPDATE Dilemma SET Status_Player{$player_num} = 2 WHERE GameId = '{$game_id}'";
mysqli_query($link, $sql_query)
?>