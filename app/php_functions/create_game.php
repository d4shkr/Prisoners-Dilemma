<?php

// Connect to database
include("../db.php");

// 1. generate uuid, 2. create game, 3. return uuid

$uuid = bin2hex(random_bytes(18));

// Game: GameId, CurrentRound, GamePhase,
// Players: PayoffHistory_Player1, PayoffHistory_Player2, Score_Player1, Score_Player2, Status_Player1, Status_Player2, Message_Player1, Message_Player2, Player1_UpToDate, Player2_UpToDate,
// Settings: MaxRounds, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff, MaxRoundsKnown 
$sql_query = "INSERT INTO Dilemma VALUES ('{$uuid}', 1, 'Running', '[]', '[]', 0, 0, -1, -1, '', '', FALSE, FALSE, 10, 1, 3, 0, 5, TRUE)";
mysqli_query($link, $sql_query);

echo $uuid;
?>

