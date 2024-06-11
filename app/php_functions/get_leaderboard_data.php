<?php

include("get_tournament_id.php");

$sql_query = "SELECT NumberOfGamesPerMember FROM Tournaments WHERE TournamentId = '{$tournament_id}'";
$number_of_games = mysqli_query($link, $sql_query)->fetch_row()[0];

// get member id, member name, score and number of played games from TournamentMembers table for each member
$sql_query = "SELECT TournamentMemberId, MemberName, Score, NumberOfPlayedGames FROM TournamentMembers WHERE TournamentId = '{$tournament_id}'";
$query_res = mysqli_query($link, $sql_query);

// dict with result
$res = array();
while ($obj = $query_res->fetch_object()) {
    $res[$obj->TournamentMemberId] = array($obj->MemberName, $obj->Score, $obj->NumberOfPlayedGames);
}

$res = array("member_id" => $member_id, "json" => $res, "number_of_games" => $number_of_games);

echo json_encode($res);
?>