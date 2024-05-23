<?php
// Get game id and player number 
include("get_game_id.php");

// Player chose to betray, so we need to set Status_Player to 1
$sql_query = "UPDATE Dilemma SET Status_Player{$player_num} = 1, UpToDate_Player{$player_num} = FALSE WHERE GameId = '{$game_id}'";
mysqli_query($link, $sql_query);

// Update scores if other player made their choice
include("update_scores.php");
?>