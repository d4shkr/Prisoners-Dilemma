<?php
include_once('db.php');

if (isset($_GET['TournamentId'])) {

    $tournament_id = $_GET['TournamentId'];

    // If user is already a member of some other tournament
    if (isset($_COOKIE['TournamentMemberId'])) {
        
        $tournament_member_id = $_COOKIE['TournamentMemberId'];
        $sql_query = "SELECT TournamentId FROM TournamentMembers WHERE TournamentMemberId = '{$tournament_member_id}'";

        // If TournamentMemberId exists:
        if ($row = mysqli_query($link, $sql_query)->fetch_row()) {
            $curr_tournament_id = $row[0];

            // If user is trying to join a different tournament, remove them from the previous tournament
            if ($curr_tournament_id != $tournament_id) {
                
                $sql_query = "SELECT TournamentMemberIds, TournamentPhase FROM Tournaments WHERE TournamentId = '{$curr_tournament_id}'";

                // Remove member from the tournament:
                if ($res = mysqli_query($link, $sql_query)->fetch_object()) {
                    $tournament_member_ids_array = json_decode($res->TournamentMemberIds);

                    // If Tournament is complete, we don't need to remove the member from the leaderboard
                    if ($res->TournamentMemberIds != 'Complete') {

                        // If we found this member in the list, remove them
                        if ($key = array_search($tournament_member_id, $tournament_member_ids_array)) {
                            unset($tournament_member_ids_array[$key]);
                            $tournament_member_ids_array = array_values($tournament_member_ids_array);
                            $tournament_member_ids_array = json_encode($tournament_member_ids_array);

                            // If game is running, we need to decrement the total number of tournament members, as users can't join the tournament anymore
                            if ($res->TournamentPhase == 'Waiting') {
                                $sql_query = "UPDATE Tournaments SET TournamentMemberIds = '{$tournament_member_ids_array}' WHERE TournamentId = '{$curr_tournament_id}'";
                            } else {
                                $sql_query = "UPDATE Tournaments SET TournamentMemberIds = '{$tournament_member_ids_array}', NumberOfMembers = NumberOfMembers - 1 WHERE TournamentId = '{$curr_tournament_id}'";
                            } 

                            mysqli_query($link, $sql_query);
                        }

                        // Remove this member from the table, as they won't be needed for the leaderboard
                        $sql_query = "DELETE FROM TournamentMembers WHERE TournamentMemberId = '{$tournament_member_id}'";
                        mysqli_query($link, $sql_query);
                    }
                }

            } else {
                
                // Send player to the tournament page
                header("Location: tournament.php");
                exit;
            }
        }
    }

    // Joining for new members of the Tournament
    $sql_query = "SELECT TournamentPhase, TournamentMemberIds, NumberOfMembers FROM Tournaments WHERE TournamentId = '{$tournament_id}'";
    // If TournamentId exists:
    if ($res = mysqli_query($link, $sql_query)->fetch_object()) {

        // We don't allow joining mid-tournament
        if ($res->TournamentPhase != 'Waiting') {
            echo "<div class='error'> Tournament has already started. </div>"; 
            exit;
        }

        $tournament_member_ids_array = json_decode($res->TournamentMemberIds);
        $number_of_members = $res->NumberOfMembers;
        $current_member_count = count($tournament_member_ids_array);

        // If there is space for a new member, add them
        if ($current_member_count < $number_of_members) {
            $uuid = bin2hex(random_bytes(18));

            // Default name, e. g. 'Player 3'
            $member_name = "Player " . ($current_member_count + 1);

            $sql_query = "INSERT INTO TournamentMembers VALUES ('{$uuid}', '{$tournament_id}', NULL, '{$member_name}', 0, 0, '[]', TRUE)";
            mysqli_query($link, $sql_query);

            // Update list of tournament members
            $tournament_member_ids_array[] = $uuid;
            $tournament_member_ids_array = json_encode($tournament_member_ids_array);
            $sql_query = "UPDATE Tournaments SET TournamentMemberIds = '{$tournament_member_ids_array}' WHERE TournamentId = '{$tournament_id}'";
            mysqli_query($link, $sql_query);

            setcookie('TournamentMemberId', $uuid, time() + 86400); // expires in a day

            // Send player to the tournament page
            header("Location: tournament.php");
            exit;

        } else {
            echo "<div class='error'> Tournament is full. </div>";
        }

    } else {
        echo "<div class='error'> Tournament Id is incorrect. </div>"; 
    }


// If TournamentId is not set, check for GameId
} else if (isset($_GET['GameId'])) {
    
    $game_id = $_GET['GameId'];

    // If user is a member of some tournament
    if (isset($_COOKIE['TournamentMemberId'])) {
        $tournament_member_id = $_COOKIE['TournamentMemberId'];
        $sql_query = "SELECT TournamentId FROM TournamentMembers WHERE TournamentMemberId = '{$tournament_member_id}'";

        // If TournamentMemberId exists:
        if ($row = mysqli_query($link, $sql_query)->fetch_row()) {
            $curr_tournament_id = $row[0];
            
            $sql_query = "SELECT TournamentMemberIds, TournamentPhase FROM Tournaments WHERE TournamentId = '{$curr_tournament_id}'";

            // Remove member from the tournament:
            if ($res = mysqli_query($link, $sql_query)->fetch_object()) {
                $tournament_member_ids_array = json_decode($res->TournamentMemberIds);

                // If Tournament is complete, we don't need to remove the member from the leaderboard
                if ($res->TournamentMemberIds != 'Complete') {

                    // If we found this member in the list, remove them
                    if ($key = array_search($tournament_member_id, $tournament_member_ids_array)) {
                        unset($tournament_member_ids_array[$key]);
                        $tournament_member_ids_array = array_values($tournament_member_ids_array);
                        $tournament_member_ids_array = json_encode($tournament_member_ids_array);

                        // If game is running, we need to decrement the total number of tournament members, as users can't join the tournament anymore
                        if ($res->TournamentPhase == 'Waiting') {
                            $sql_query = "UPDATE Tournaments SET TournamentMemberIds = '{$tournament_member_ids_array}' WHERE TournamentId = '{$curr_tournament_id}'";
                        } else {
                            $sql_query = "UPDATE Tournaments SET TournamentMemberIds = '{$tournament_member_ids_array}', NumberOfMembers = NumberOfMembers - 1 WHERE TournamentId = '{$curr_tournament_id}'";
                        } 

                        mysqli_query($link, $sql_query);
                    }

                    // Remove this member from the table, as they won't be needed for the leaderboard
                    $sql_query = "DELETE FROM TournamentMembers WHERE TournamentMemberId = '{$tournament_member_id}'";
                    mysqli_query($link, $sql_query);
                }
            }
        }
        // remove tournament cookie
        unset($_COOKIE['TournamentMemberId']); 
        setcookie('TournamentMemberId', '', -1); 
    }

    if (isset($_COOKIE['PlayerId'])) {

        // get GameId based on PlayerId
        $player_id = $_COOKIE['PlayerId'];
        $sql_query = "SELECT GameId FROM Players WHERE PlayerId = '{$player_id}'";

        if ($row = mysqli_query($link, $sql_query)->fetch_row()) {

            // if GameId is different, remove PlayerId from Players, set GamePhase finished; 
            if ($row[0] != $game_id) {
                $sql_query = "DELETE FROM Players WHERE PlayerId = '{$player_id}'";
                mysqli_query($link, $sql_query);

                $sql_query = "UPDATE Dilemma SET GamePhase = 'Finished', UpToDate_Player1 = FALSE, UpToDate_Player2 = FALSE, Message_Player1 = 'Your opponent left the game...', Message_Player2 = 'Your opponent left the game...' WHERE GameId = '{$row[0]}'";
                mysqli_query($link, $sql_query);

            // if GameId is the same, send player to the game page
            } else {
                header("Location: dilemma.php");
                exit;
            }
        }
    }

    $sql_query = "SELECT Status_Player1, Status_Player2 FROM Dilemma WHERE GameId = '{$game_id}'"; // check if this game id exists
    if ($row = mysqli_query($link, $sql_query)->fetch_object()) {

        // Check if game is available (if Status_Player == -1)
        if ($row->Status_Player1 == -1) { 

            // initialize player1
            $sql_query = "UPDATE Dilemma SET Status_Player1 = 0 WHERE GameId = '{$game_id}'";
            mysqli_query($link, $sql_query);

            // Add Player 1 to Players table
            $uuid = bin2hex(random_bytes(18));
            $sql_query = "INSERT INTO Players VALUES ('{$uuid}', '{$game_id}', 1)";
            mysqli_query($link, $sql_query);

            setcookie('PlayerId', $uuid, time() + 86400); // expires in a day
            header("Location: dilemma.php"); // send player to the game page

        } else if ($row->Status_Player2 == -1) { 

            // initialize player2
            $sql_query = "UPDATE Dilemma SET Status_Player2 = 0 WHERE GameId = '{$game_id}'";
            mysqli_query($link, $sql_query);

            // Add Player 2 to Players table
            $uuid = bin2hex(random_bytes(18));
            $sql_query = "INSERT INTO Players VALUES ('{$uuid}', '{$game_id}', 2)";
            mysqli_query($link, $sql_query);

            setcookie('PlayerId', $uuid, time() + 86400); // expires in a day
            header("Location: dilemma.php"); // send player to the game page
            exit;

        } else {
            echo "<div class='error'> Game is full. </div>";
        }
        
    } else {
        echo "<div class='error'> Game Id is incorrect. </div>";
    }

} else {
    echo "<div class='error'> No Game or Tournament Id provided. </div>";
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
    <link rel="stylesheet" href="css/dilemma.css">
    
</head>
</html>