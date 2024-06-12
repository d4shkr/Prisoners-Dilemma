<?php

// Connect to database
include("../db.php");

// Get tournament settings from POST

$number_of_rounds = $_POST["number_of_rounds"];
$hide_rounds_num = $_POST["hide_rounds_num"];
$both_cooperate_payoff = $_POST["both_cooperate_payoff"];
$both_betray_payoff = $_POST["both_betray_payoff"];
$was_betrayed = $_POST["was_betrayed_payoff"];
$has_betrayed = $_POST["has_betrayed_payoff"];
$number_of_members = $_POST["number_of_players"];
$number_of_games = $_POST["number_of_games"];

$max_round_known = $hide_rounds_num == "true" ? "FALSE" : "TRUE";

// 1. generate uuid, 2. create game, 3. return uuid

$uuid = bin2hex(random_bytes(18));

// Tournament: TournamentId, TournamentMemberIds, TournamentPhase
// Tournament Settings: NumberOfMembers, NumberOfGamesPerMember 
// Game Settings: MaxRounds, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff, MaxRoundsKnown 
$sql_query = "INSERT INTO Tournaments VALUES ('{$uuid}', '[]', 'Waiting', {$number_of_members}, {$number_of_games}, {$number_of_rounds}, {$both_betray_payoff}, {$both_cooperate_payoff}, {$was_betrayed}, {$has_betrayed}, {$max_round_known})";
mysqli_query($link, $sql_query);

echo $uuid;
?>

