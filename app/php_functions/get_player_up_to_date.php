<?php
// This function returns whether the player needs to update the visuals

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT UpToDate_Player{$player_num} FROM Dilemma WHERE GameId = '{$game_id}'";
$up_to_date = mysqli_query($link, $sql_query)->fetch_row()[0];

echo $up_to_date;

if (!$up_to_date) { 
    $sql_query = "UPDATE Dilemma SET UpToDate_Player{$player_num} = TRUE WHERE GameId = '{$game_id}'";
    mysqli_query($link, $sql_query);
}
?>