<?php

// Connect to database
include("../db.php");

// Get game settings from POST

$number_of_rounds = $_POST["number_of_rounds"];
$hide_rounds_num = $_POST["hide_rounds_num"];
$both_cooperate_payoff = $_POST["both_cooperate_payoff"];
$both_betray_payoff = $_POST["both_betray_payoff"];
$was_betrayed = $_POST["was_betrayed_payoff"];
$has_betrayed = $_POST["has_betrayed_payoff"];

// If the player chose to hide the number of rounds, we make it a bit smaller or bigger
if ($hide_rounds_num == "true") {
    $var = intdiv($number_of_rounds, 2); // variate at most by half of the number of rounds
    $number_of_rounds += rand(-$var, $var);
}

$max_round_known = $hide_rounds_num == "true" ? "FALSE" : "TRUE";

// 1. generate uuid, 2. create game, 3. return uuid

$uuid = bin2hex(random_bytes(18));

// Game: GameId, CurrentRound, GamePhase,
// Players: PayoffHistory_Player1, PayoffHistory_Player2, Score_Player1, Score_Player2, Status_Player1, Status_Player2, Message_Player1, Message_Player2, UpToDate_Player1, UpToDate_Player2,
// Settings: MaxRounds, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff, MaxRoundsKnown 
$sql_query = "INSERT INTO Dilemma VALUES ('{$uuid}', 1, 'Running', '[]', '[]', 0, 0, -1, -1, '', '', FALSE, FALSE, {$number_of_rounds}, {$both_betray_payoff}, {$both_cooperate_payoff}, {$was_betrayed}, {$has_betrayed}, {$max_round_known})";
mysqli_query($link, $sql_query);

echo $uuid;
?>

