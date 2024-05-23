<?php
// Set payoffs in the current round based on choices made

// get total scores, status values, payoff histories and round number from the table 
$sql_query = "SELECT Score_Player1, Score_Player2, Status_Player1, Status_Player2, PayoffHistory_Player1, PayoffHistory_Player2, CurrentRound, MaxRounds, GamePhase, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff FROM Dilemma WHERE GameId = '{$game_id}'";

$res = mysqli_query($link, $sql_query)->fetch_object();

$status1 = $res->Status_Player1;
$status2 = $res->Status_Player2;
$score1 = $res->Score_Player1;
$score2 = $res->Score_Player2;
$payoff_history1 = json_decode($res->PayoffHistory_Player1);
$payoff_history2 = json_decode($res->PayoffHistory_Player2);
$curr_round = $res->CurrentRound;
$max_round = $res->MaxRounds;
$both_betray = $res->BothBetrayPayoff;
$both_cooperate = $res->BothCooperatePayoff;
$was_betrayed = $res->WasBetrayedPayoff;
$has_betrayed = $res->HasBetrayedPayoff;

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

// Constructing the messages
$both_cooperated_message = "You both cooperated! " . ($both_cooperate == 0 ? "The score doesn't change." : "You " . ($both_cooperate > 0 ? "gain" : "lose") . " {$both_cooperate} point" . (abs($both_cooperate) > 1 ? "s each." : " each."));
$both_betrayed_message = "You betrayed each other! " . ($both_betray == 0 ? "The score doesn't change." : "You both " . ($both_betray > 0 ? "gain" : "lose") . " {$both_betray} point" . (abs($both_betray) > 1 ? "s." : "."));
$has_betrayed_message = "You betrayed your opponent!" . ($was_betrayed == 0 && $has_betrayed == 0 ? " The score doesn't change." : ($has_betrayed == 0 ? "" : " You " . ($has_betrayed > 0 ? "gain" : "lose") . " {$has_betrayed} point" . (abs($has_betrayed) > 1 ? "s." : ".")) . ($was_betrayed == 0 ? "" : " They " . ($was_betrayed > 0 ? "gain" : "lose") . " {$was_betrayed} point" . (abs($was_betrayed) > 1 ? "s." : ".")));
$was_betrayed_message = "You were betrayed!" . ($was_betrayed == 0 && $has_betrayed == 0 ? " The score doesn't change." : ($was_betrayed == 0 ? "" : " You " . ($was_betrayed > 0 ? "gain" : "lose") . " {$was_betrayed} point" . (abs($was_betrayed) > 1 ? "s." : ".")) . ($has_betrayed == 0 ? "" : " Your opponent " . ($has_betrayed > 0 ? "gains" : "loses") . " {$has_betrayed} point" . (abs($has_betrayed) > 1 ? "s." : ".")));

// if both cooperate:
if ($status1 == 2 && $status2 == 2) {
    $payoff1 = $both_cooperate;
    $payoff2 = $both_cooperate;
    $message1 = $both_cooperated_message;
    $message2 = $both_cooperated_message;
// if both betray:
} else if ($status1 == 1 && $status2 == 1) {
    $payoff1 = $both_betray;
    $payoff2 = $both_betray;
    $message1 = $both_betrayed_message;
    $message2 = $both_betrayed_message;
// if player 1 betrays and player 2 cooperates:
} else if ($status1 == 1 && $status2 == 2) {
    $payoff1 = $has_betrayed;
    $payoff2 = $was_betrayed;
    $message1 = $has_betrayed_message;
    $message2 = $was_betrayed_message;
// if player 1 cooperates and player 2 betrays:
} else if ($status1 == 2 && $status2 == 1) {
    $payoff1 = $was_betrayed;
    $payoff2 = $has_betrayed;
    $message1 = $was_betrayed_message;
    $message2 = $has_betrayed_message;
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
$sql_query = "UPDATE Dilemma SET CurrentRound = CurrentRound + 1, Status_Player1 = 0, Status_Player2 = 0, Score_Player1 = {$score1}, Score_Player2 = {$score2}, PayoffHistory_Player1 = '{$payoff_history1}', PayoffHistory_Player2 = '{$payoff_history2}', Message_Player1 = '{$message1}', Message_Player2 = '{$message2}', UpToDate_Player1 = FALSE, UpToDate_Player2 = FALSE  WHERE GameId = '{$game_id}'";
mysqli_query($link, $sql_query);


?>