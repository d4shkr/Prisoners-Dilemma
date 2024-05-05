<?php

include("../db.php");
include("../game.php");

// 1. generate uuid, 2. create game, 3. return uuid

$uuid = UUID::v4();
$sql_query = "INSERT INTO Dilemma VALUES ({$uuid}, \{\}, 0, 0, -1, -1, FALSE, FALSE, 0, 10, 'Running')";
// need to insert json to PayoffHistoryTable
mysqli_query($sql_query, $link);

echo $uuid;
?>

