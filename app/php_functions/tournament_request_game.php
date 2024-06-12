<?php
/*
1. all members have played all their games: 'complete'
2. if user played all their games: 'done'
3. if user is already in an unfinished game: 'playing'
4. if user is not in a game or is in a finished game:
    a) if there is a fitting (have not played recently) free member, create game: 'playing'
    b) else: 'waiting'
*/

// Connect to database
include("../db.php");

session_start();
$member_id = $_SESSION['TournamentMemberId'];

// Get TournamentId, Player id, player availability, number of played games and list of previous opponents 
$sql_query = "SELECT TournamentId, Curr_PlayerId, IsAvailable, NumberOfPlayedGames, PreviousOpponentIds FROM TournamentMembers WHERE TournamentMemberId = '{$member_id}'";
$res = mysqli_query($link, $sql_query)->fetch_object();

$gamenum = $res->NumberOfPlayedGames;
$opponents = $res->PreviousOpponentIds;
$player_id = $res->Curr_PlayerId;
$tournament_id = $res->TournamentId;
$is_available = $res->IsAvailable;

// Get tournament phase, number of members, number of games per member and tournament settings
$sql_query = "SELECT TournamentPhase, NumberOfMembers, TournamentMemberIds, NumberOfGamesPerMember, MaxRounds, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff, MaxRoundsKnown FROM Tournaments WHERE TournamentId = '{$tournament_id}'";
$tournament_res = mysqli_query($link, $sql_query)->fetch_object();

$phase = $tournament_res->TournamentPhase;
$games_per_member = $tournament_res->NumberOfGamesPerMember;
$number_of_members = $tournament_res->NumberOfMembers;

// 1. all members have played all their games: 'complete'

if ($phase == 'Complete') {
    echo 'complete';
    exit;
}

// 2. if user played all their games: 'done'

if ($gamenum == $games_per_member) {
    echo 'done';
    exit;
}

// 3. if user is already in an unfinished game: 'playing'

if (!$is_available) {
    echo 'playing';
    exit;
}

if ($phase == 'Waiting') {
    echo 'joining';
    exit;
}

// 4. if user is not in a game or is in a finished game:
    // a) if there is a fitting (have not played recently) free member, create game: 'playing'
    // b) else: 'waiting'

$opponents_tuple_string = substr($opponents, 1, strlen($opponents) - 2);    
// Get tournament member id and PreviousOpponentIds of all probably available opponents
if (empty($opponents_tuple_string)) {
    $sql_query = "SELECT TournamentMemberId, PreviousOpponentIds FROM TournamentMembers WHERE IsAvailable = TRUE AND TournamentId = '{$tournament_id}' AND TournamentMemberId != '{$member_id}'";
} else {
    $sql_query = "SELECT TournamentMemberId, PreviousOpponentIds FROM TournamentMembers WHERE IsAvailable = TRUE AND TournamentId = '{$tournament_id}' AND TournamentMemberId NOT IN ('{$member_id}', {$opponents_tuple_string})";
}
// we need to check whether we are NOT in our probable opponent's 'black list', so we have to run through all probably available opponents until we find 'the one'
while ($res = mysqli_query($link, $sql_query)->fetch_object()) {
    $opponent_id = $res->TournamentMemberId;
    $black_list = json_decode($res->PreviousOpponentIds);

    // if the user is NOT in this list, we can pair these members and create a new game
    if (!in_array($member_id, $black_list)) {

        // CREATE GAME :

        // generate game id and player ids
        $game_uuid = bin2hex(random_bytes(18));
        $player1_uuid = bin2hex(random_bytes(18));
        $player2_uuid = bin2hex(random_bytes(18));

        // get tournament settings to copy them
        $max_rounds = $tournament_res->MaxRounds;
        $both_betray = $tournament_res->BothBetrayPayoff;
        $both_cooperate = $tournament_res->BothCooperatePayoff;
        $was_betrayed = $tournament_res->WasBetrayedPayoff;
        $has_betrayed = $tournament_res->HasBetrayedPayoff;
        $max_rounds_known = $tournament_res->MaxRoundsKnown ? "TRUE" : "FALSE";

        // Game: GameId, CurrentRound, GamePhase,
        // Players: PayoffHistory_Player1, PayoffHistory_Player2, Score_Player1, Score_Player2, Status_Player1, Status_Player2, Message_Player1, Message_Player2, UpToDate_Player1, UpToDate_Player2, TournamentMemberId1, TournamentMemberId2
        // Settings: MaxRounds, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff, MaxRoundsKnown 
        $sql_query = "INSERT INTO Dilemma VALUES ('{$game_uuid}', 1, 'Running', '[]', '[]', 0, 0, 0, 0, '', '', FALSE, FALSE, '{$member_id}', '{$opponent_id}', {$max_rounds}, {$both_betray}, {$both_cooperate}, {$was_betrayed}, {$has_betrayed}, {$max_rounds_known})";
        mysqli_query($link, $sql_query);

        // Create two rows in the "Players" table
        $sql_query = "INSERT INTO Players VALUES ('{$player1_uuid}', '{$game_uuid}', 1), ('{$player2_uuid}', '{$game_uuid}', 2)";
        mysqli_query($link, $sql_query);

        // Now we need to update the "TournamentMembers" table.
        
        // we need to add new opponents to each other's 'black list'. We'll add a new opponent and cut the list if it's too long
        if ($number_of_members >= 3) {
            $opponents = json_decode($opponents);
            $opponents[] = $opponent_id; // to the end of the list (FIFO)
            // (is FIFO clamped to NumberOfMembers - 3):
            while (count($opponents) + 3 > $number_of_members) {
                array_shift($opponents);
            }

            $black_list[] = $member_id;
            while (count($black_list) + 3 > $number_of_members) {
                array_shift($black_list);
            }
        }
        $opponents = json_encode($opponents);
        $black_list = json_encode($black_list);

        // update table: set a new game to the members (update player id)
        $sql_query = "UPDATE TournamentMembers SET Curr_PlayerId = '{$player1_uuid}', PreviousOpponentIds = '{$opponents}', IsAvailable = FALSE WHERE TournamentMemberId = '{$member_id}'";
        mysqli_query($link, $sql_query);
        $sql_query = "UPDATE TournamentMembers SET Curr_PlayerId = '{$player2_uuid}', PreviousOpponentIds = '{$black_list}', IsAvailable = FALSE WHERE TournamentMemberId = '{$opponent_id}'";
        mysqli_query($link, $sql_query);

        echo 'playing';
        exit;
    }
}

echo 'waiting';
exit;
?>