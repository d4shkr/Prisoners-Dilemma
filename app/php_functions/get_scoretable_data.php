<?php
include("get_game_id.php");

$opponent_num = 3 - $player_num;
$sql_query = "SELECT Score_Player{$player_num}, Score_Player{$opponent_num}, PayoffHistory_Player{$player_num}, PayoffHistory_Player{$opponent_num} FROM Dilemma WHERE GameId = '{$game_id}'";

echo implode('|', mysqli_query($link, $sql_query)->fetch_row()); // format: "YourScore|OpponentScore|YourPayoffHistory|OpponentPayoffHistory"
?>