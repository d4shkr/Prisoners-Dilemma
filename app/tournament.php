<?php
include_once('db.php');

if (!isset($_COOKIE['TournamentMemberId'])) {

  echo "<div class='error'> Tournament Member Id is missing </div>";
  exit;
}

session_start();
$_SESSION['TournamentMemberId'] = $_COOKIE['TournamentMemberId']; // if something goes wrong or the player decides to clear cookies during the game

$tournament_member_id = $_SESSION['TournamentMemberId'];

// Check if TournamentMemberId and TournamentId are valid
$sql_query = "SELECT TournamentId FROM TournamentMembers WHERE TournamentMemberId = '{$tournament_member_id}'";

if ($res = mysqli_query($link, $sql_query)->fetch_row()) {
  $res = $res[0];
  $sql_query = "SELECT TournamentId FROM Tournaments WHERE TournamentId = '{$res}'";

  if (!mysqli_query($link, $sql_query)->fetch_row()) { // if TournamentId in not in Tournaments table
    echo "<div class='error'> Tournament Id is incorrect </div>";
    exit;
  }
} else {
  echo "<div class='error'> Tournament Member Id doesn't exist </div>";
  exit;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Tournament</title>
    <link rel="stylesheet" href="css/tournament.css">
  </head>

  <body>
    <!-- Navigation bar -->
    <nav>
    <div id='for-gamenum'> <div id='gamenum'> <h2> Tournament</h2> </div> </div>
      <ul>
        <li><a id='guide'> Guide </a> </li>
        <li><a id='home-link'> Home </a> </li>
        <li><a id='about'> About </a> </li>
      </ul>
    </nav>
    <!-- Tournament page -->
    <main>
        <article>

            <!-- Leaderboard -->
            <div id='leaderboard'>
                <div id='headers'>
                    <div class='place'> Place </div>
                    <div class='name'> Name </div>
                    <div class='score'> Score </div>
                    <div class='gamenum'> Games </div>
                </div>
                <div class='row' id='player_1'>
                    <div class='place'> 1 </div>
                    <div class='name'> Masha </div>
                    <div class='score'> 0 </div>
                    <div class='gamenum'> 0 / 10 </div>
                </div>
                <div class='row' id='player_2'>
                    <div class='place'> 2 </div>
                    <div class='name'> Player 2 </div>
                    <div class='score'> 50 </div>
                    <div class='gamenum'> 10 / 10 </div>
                </div>
            </div>

        </article>

        <aside>
            <!-- Change nickname -->
            <div class='container'>
                <input type='text' id='nickname' value='' placeholder='Change your name here!' maxlength='16' size='16'> <div id='pencil'> ✏️ </div>
            </div>

            <!-- Button area -->

            <div id='for-button-and-message'>
              <!-- Join button-->
              <div id='join-button'>
                <a href='/dilemma.php'>
                  <div class='button' id='join'>
                      <p> Join Game </p>
                  </div>
                </a>
              </div>

              <!-- Waiting message -->
              <div class='for-loader-and-message'>
                <div class='for-waiting-message' id='waiting'>
                  Waiting for an available player... 
                </div>
                <div class='loader-container' id='loader'>
                    <div class='loader-2'></div>
                </div>
              </div>
            </div>

            <!-- Settings and Payoff matrix -->

            <?php
            // get Tournament Id from Member ID
            $member_id = $_SESSION['TournamentMemberId'];
            $sql_query = "SELECT TournamentId FROM TournamentMembers WHERE TournamentMemberId = '{$member_id}'";
            $res = mysqli_query($link, $sql_query)->fetch_object();

            $tournament_id = $res->TournamentId;

            // Get setting ans payoffs 
            $sql_query = "SELECT NumberOfMembers, NumberOfGamesPerMember, MaxRounds, MaxRoundsKnown, BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff FROM Tournaments WHERE TournamentId = '{$tournament_id}'";
            $res = mysqli_query($link, $sql_query)->fetch_object();

            // prepare for building the 'settings' table. if the number of rounds in a game is hidden, display '???' in that column
            $rounds = $res->MaxRounds;
            $is_known = $res->MaxRoundsKnown;
            if (!$is_known) {
              $var = intdiv($rounds, 2);
              if ($var != 0) {        
                $rounds = ($rounds - $var) . '-' . ($rounds + $var);
              }
            }

            echo "<table id='payoff'> 
              <caption> Payoff matrix </caption>
              <tr> 
                  <th scope='col'> Pl1, Pl2 </th>
                  <th scope='col'> Cooperate </th>  
                  <th scope='col'> Betray </th>  
              </tr>
              <tr> 
                  <th scope='row'> Cooperate </th> 
                  <td> {$res->BothCooperatePayoff}, {$res->BothCooperatePayoff}</td> 
                  <td> {$res->WasBetrayedPayoff}, {$res->HasBetrayedPayoff}</td>
              </tr>
              <tr> 
                  <th scope='row'> Betray </th> 
                  <td> {$res->HasBetrayedPayoff}, {$res->WasBetrayedPayoff}</td> 
                  <td> {$res->BothBetrayPayoff}, {$res->BothBetrayPayoff}</td> 
              </tr>
          </table>
          
          <table id='settings'>
            <caption> Tournament settings </caption>
            <tr>
              <th scope='col'> Members </th>
              <td> {$res->NumberOfMembers} </td>
            </tr>
            <tr>
              <th scope='col'> Rounds </th>
              <td> {$rounds} </td>
            </tr>
          </table>
          ";
          ?>
            
        </aside>
    </main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="scripts/tournament.js"></script>
</body>
</html>