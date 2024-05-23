<?php
// Set payoffs in the current round based on choices made

// get total scores, status values, payoff histories and round number from the table 
$sql_query = "SELECT Score_Player1, Score_Player2, Status_Player1, Status_Player2, PayoffHistory_Player1, PayoffHistory_Player2, CurrentRound, MaxRounds, GamePhase FROM Dilemma WHERE GameId = '{$game_id}'";

$res = mysqli_query($link, $sql_query)->fetch_object();

$status1 = $res->Status_Player1;
$status2 = $res->Status_Player2;
$score1 = $res->Score_Player1;
$score2 = $res->Score_Player2;
$payoff_history1 = json_decode($res->PayoffHistory_Player1);
$payoff_history2 = json_decode($res->PayoffHistory_Player2);
$curr_round = $res->CurrentRound;
$max_round = $res->MaxRounds;

if ($res->GamePhase == 'Finished') {
    exit;
}

// Continue only if both players made their choice
if ($status1 <= 0 || $status2 <= 0) {
    exit;
}

// Set the game status to "Finished" if this round is the last one
if ($curr_round == $max_round) {
    $sql_query = "UPDATE Dilemma SET GamePhase = 'Finished' WHERE GameId = '{$game_id}'";
    mysqli_query($link, $sql_query);
}

// if both cooperate:
if ($status1 == 2 && $status2 == 2) {
    $payoff1 = -1;
    $payoff2 = -1;
    $message1 = "You both cooperated! You lose 1 point each.";
    $message2 = "You both cooperated! You lose 1 point each.";
// if both betray:
} else if ($status1 == 1 && $status2 == 1) {
    $payoff1 = -2;
    $payoff2 = -2;
    $message1 = "You betrayed each other! You both lose 2 points.";
    $message2 = "You betrayed each other! You both lose 2 points.";
// if player 1 betrays and player 2 cooperates:
} else if ($status1 == 1 && $status2 == 2) {
    $payoff1 = 0;
    $payoff2 = -3;
    $message1 = "You betrayed your opponent! They lose 3 points.";
    $message2 = "You were betrayed! You lose 3 points.";
// if player 1 cooperates and player 2 betrays:
} else if ($status1 == 2 && $status2 == 1) {
    $payoff1 = -3;
    $payoff2 = 0;
    $message1 = "You were betrayed! You lose 3 points.";
    $message2 = "You betrayed your opponent! They lose 3 points.";
}

// Add payoffs to total scores
$score1 += $payoff1;
$score2 += $payoff2;

// Append row with payoffs in this round
$payoff_history1[] = $payoff1;
$payoff_history2[] = $payoff2;

// Prepare Payoff history tables for insertion
$payoff_history1 = json_encode($payoff_history1);
$payoff_history2 = json_encode($payoff_history2);

// Update values in Score table
// + set statuses to 0 for the next round
// + increment current round number
$sql_query = "UPDATE Dilemma SET CurrentRound = CurrentRound + 1, Status_Player1 = 0, Status_Player2 = 0, Score_Player1 = {$score1}, Score_Player2 = {$score2}, PayoffHistory_Player1 = '{$payoff_history1}', PayoffHistory_Player2 = '{$payoff_history2}', Message_Player1 = '{$message1}', Message_Player2 = '{$message2}' WHERE GameId = '{$game_id}'";
mysqli_query($link, $sql_query);


?>