<?php
// Get game id and player number 
include("get_game_id.php");

$opponent_num = 3 - $player_num;
// get from the table: your score, other player's score, your payoff history and other player's payoff history
$sql_query = "SELECT Score_Player{$player_num}, Score_Player{$opponent_num}, PayoffHistory_Player{$player_num}, PayoffHistory_Player{$opponent_num} FROM Dilemma WHERE GameId = '{$game_id}'";

echo implode('|', mysqli_query($link, $sql_query)->fetch_row()); // format: "YourScore|OpponentScore|YourPayoffHistory|OpponentPayoffHistory"
?>