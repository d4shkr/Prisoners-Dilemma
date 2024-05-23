<?php

// Connect to database
include("../db.php");

// 1. generate uuid, 2. create game, 3. return uuid

$uuid = bin2hex(random_bytes(18));
$sql_query = "INSERT INTO Dilemma VALUES ('{$uuid}', '[]', '[]', 0, 0, -1, -1, FALSE, FALSE, 1, 3, 'Running')";
mysqli_query($link, $sql_query);

echo $uuid;
?>

