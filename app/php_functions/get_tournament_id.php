<?php
// Connect to database
include("../db.php");

// get tournament id
session_start();
$member_id = $_SESSION['TournamentMemberId'];
$sql_query = "SELECT TournamentId FROM TournamentMembers WHERE TournamentMemberId = '{$member_id}'";
$tournament_id = mysqli_query($link, $sql_query)->fetch_row()[0];
?>