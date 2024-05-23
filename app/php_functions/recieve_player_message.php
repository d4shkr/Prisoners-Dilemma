<?php
// This function gets the message that should be displayed to the player and removes it

// Get game id and player number 
include("get_game_id.php");

$sql_query = "SELECT Message_Player{$player_num} FROM Dilemma WHERE GameId = '{$game_id}'";
$message = mysqli_query($link, $sql_query)->fetch_row()[0];

echo $message;

if (!empty($message)) { // if there was a message, remove it
    $sql_query = "UPDATE Dilemma SET Message_Player{$player_num} = '', UpToDate_Player{$player_num} = FALSE WHERE GameId = '{$game_id}'";
    mysqli_query($link, $sql_query);
}
?>