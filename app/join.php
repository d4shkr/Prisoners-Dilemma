<?php
include_once('db.php');

if (isset($_GET['GameId'])) {
    
    $game_id = $_GET['GameId'];

    if (isset($_SESSION['PlayerId'])) {

        // get GameId based on PlayerId
        $player_id = $_SESSION['PlayerId'];
        $sql_query = "SELECT Curr_GameId FROM Players WHERE PlayerId = '{$player_id}'";

        if ($row = mysqli_query($link, $sql_query)->fetch_row()) {

            // if GameId is different, remove PlayerId from Players, set GamePhase finished; 
            if ($row[1] != $game_id) {
                $sql_query = "DELETE FROM Players WHERE PlayerId = '{$player_id}'";
                mysqli_query($link, $sql_query);

                $sql_query = "UPDATE Dilemma SET GamePhase = 'Finished' WHERE GameId = '{$row[1]}'";
                mysqli_query($link, $sql_query);

            // if GameId is the same, send player to the game page
            } else {
                header("Location: dilemma.php");
            }
        }
    }

    $sql_query = "SELECT * FROM Dilemma WHERE GameId = '{$game_id}'"; // check if this game id exists
    if ($row = mysqli_query($link, $sql_query)->fetch_row()) {

        // Check if game is available (if Status_Player == -1)
        if ($row[5] == -1) { 

            // initialize player1
            $sql_query = "UPDATE Dilemma SET Status_Player1 = 0 WHERE GameId = '{$game_id}'";
            mysqli_query($link, $sql_query);

            // Add Player 1 to Players table
            $uuid = bin2hex(random_bytes(18));
            $sql_query = "INSERT INTO Players VALUES ('{$uuid}', '{$game_id}', 1)";
            mysqli_query($link, $sql_query);

            $_SESSION['PlayerId'] = $uuid;
            header("Location: dilemma.php"); // send player to the game page

        } else if ($row[6] == -1) { 

            // initialize player2
            $sql_query = "UPDATE Dilemma SET Status_Player2 = 0 WHERE GameId = '{$game_id}'";
            mysqli_query($link, $sql_query);

            // Add Player 2 to Players table
            $uuid = bin2hex(random_bytes(18));
            $sql_query = "INSERT INTO Players VALUES ('{$uuid}', '{$game_id}', 2)";
            mysqli_query($link, $sql_query);

            $_SESSION['PlayerId'] = $uuid;
            header("Location: dilemma.php"); // send player to the game page

        } else {
            echo "Game is full.";
        }
        
    } else {
        echo "Game Id is incorrect.";
    }
} else {
    echo "No Game Id provided.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0" />
    <title>Join Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
</html>