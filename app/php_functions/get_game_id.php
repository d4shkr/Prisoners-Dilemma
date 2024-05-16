<?php
include("../db.php");

// get Game Id from Player ID
session_start();
$player_id = $_SESSION['PlayerId'];
$sql_query = "SELECT Curr_GameId, Curr_PlayerNum FROM Players WHERE PlayerId = '{$player_id}'";
$res = mysqli_query($link, $sql_query)->fetch_object();

$game_id = $res->Curr_GameId;
// also get your player number in this game
$player_num = $res->Curr_PlayerNum;
?>