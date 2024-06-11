<?php

// Connect to database
include("../db.php");

// get tournament member id
session_start();
$member_id = $_SESSION['TournamentMemberId'];

$new_name = $_POST["name"];

$sql_query = "UPDATE TournamentMembers SET MemberName = '{$new_name}' WHERE TournamentMemberId = '{$member_id}'";
mysqli_query($link, $sql_query);
?>