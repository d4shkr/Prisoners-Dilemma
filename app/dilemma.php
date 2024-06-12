<?php
include_once('db.php');

session_start();
$player_id = NULL;
if (isset($_COOKIE['TournamentMemberId'])) {
  $member_id = $_COOKIE['TournamentMemberId'];
  $sql_query = "SELECT Curr_PlayerId FROM TournamentMembers WHERE TournamentMemberId = '{$member_id}'";
  if ($res = mysqli_query($link, $sql_query)->fetch_row()) {
    $_SESSION['PlayerId'] = $res[0];
    $player_id = $res[0];
  }
}

if (!isset($player_id)) {
  if (isset($_COOKIE['PlayerId'])) {
    $_SESSION['PlayerId'] = $_COOKIE['PlayerId'];
    $player_id = $_COOKIE['PlayerId'];
  } else {
    echo "<div class='error'> Player Id is missing </div>";
    exit;
  }
}

$player_id = $_SESSION['PlayerId'];

// Check if PlayerId and GameId are valid
$sql_query = "SELECT GameId FROM Players WHERE PlayerId = '{$player_id}'";

if ($res = mysqli_query($link, $sql_query)->fetch_row()) {
  $res = $res[0];
  $sql_query = "SELECT GameId FROM Dilemma WHERE GameId = '{$res}'";

  if (!mysqli_query($link, $sql_query)->fetch_row()) { // if GameId in not in Dilemma table
    echo "<div class='error'> Game Id is incorrect </div>";
    exit;
  }
} else {
  echo "<div class='error'> Player Id doesn't exist </div>";
  exit;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Play Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/payoff.css">
    <link rel="stylesheet" href="css/dilemma.css">
  </head>

  <body>
    <!-- Navigation bar -->
    <nav>
    <div id='for-round'> <div id='round'> <h2> Round 1 </h2> </div> </div>
      <ul>
        <li><a id='guide'> Guide </a> </li>
        <li><a href="/index.php" id='home-link'> Home </a> </li>
      </ul>
    </nav>

    <!-- Guide -->
    <div id='guide-page' class='dim no-display'>
        <div class='window'> 
            <p> You will play a Prisoner's Dilemma game repeatedly. </p>
            <p> In this game, you and another player must decide simultaneously either to <b style='color: #7dca6e'>cooperate</b> or to <b style='color: #fb89b7'>betray</b>.</p> 
            <p> Your goal is to maximize <b> your </b> score, <b> not </b> to get more points than your opponent. </p>
            <p> <b style='color:#912F56'>Payoff Matrix</b> displays the change in scores in every scenario. </p>
            <p> Do you want to <b style='color: #7dca6e'>cooperate</b> or to <b style='color: #fb89b7'>betray</b>? Make your next move! </p>
        </div>
    </div>

    <!-- Game page -->
    <main>
      <article>
        <p> 
          You will play a prisoner's dilemma game repeatedly. 
          In this game, you and another player must decide simultaneously either to "cooperate" or to "betray". 
        </p>

      <!-- Payoff Matrix -->
      <!-- Создаем таблицу вида:
        |            |    Молчать   |     Сдать      |
        |  Молчать   |    (-1,-1)   |    (-3, 0)     |
        |  Сдать     |    (0, -3)   |    (-2,-2)     |
      -->
      <!--
        <p> Corresponding payoffs are determined as follows: For one shot of the game, if both players compete, they both get a payoff equal to -2.
          If both cooperate, they both get -1. If one cooperates and the other competes, the first one gets -3 and the second gets 0. </p>
        -->
        <?php
          // get Game Id from Player ID
          $player_id = $_SESSION['PlayerId'];
          $sql_query = "SELECT GameId FROM Players WHERE PlayerId = '{$player_id}'";
          $res = mysqli_query($link, $sql_query)->fetch_object();

          $game_id = $res->GameId;

          // Get payoffs
          $sql_query = "SELECT BothBetrayPayoff, BothCooperatePayoff, WasBetrayedPayoff, HasBetrayedPayoff FROM Dilemma WHERE GameId = '{$game_id}'";
          $res = mysqli_query($link, $sql_query)->fetch_object();

          echo "<table id='payoff'> 
            <caption> Payoff matrix </caption>
            <tr> 
                <th scope='col'> You \ Opp </th>
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
        </table>";
        ?>
        
      <p> Do you want to cooperate or to betray? Please make your next move. </p>

      <div id='for-buttons-and-waiting-message'>
      <!-- Action Buttons -->
      <div id='action_buttons' class='collapsed'>
        <div class='button' id='cooperate'>
            <p> Cooperate </p>
        </div>
        <div class='button' id='betray'>
            <p> Betray </p>
        </div>
      </div>
      <a id="tournament-link" href="/tournament.php" class="collapsed">
        <div id='tournament-button' class='button'>
          <p> Return to Tournament page </p>
        </div>
      </a>

      <!-- Waiting message -->
      <div class='for-loader-and-message'>
        <div class='for-waiting-message collapsed' id='waiting'>
          Waiting for other player to choose... 
        </div>
        <div class='loader-container collapsed' id='loader'>
            <div class='loader-2'></div>
        </div>
      </div>
</div>
    
      </article>

      <!-- Score Table -->

      <aside>
        <div id='for-score-table'>
        <table id='score'>
        <caption> Score </caption>
            <tr> 
                <th scope='col'>  </th>
                <th scope='col'> You </th>  
                <th scope='col'> Opponent </th>  
            </tr>
            <tr> 
                <th scope='row'> Score </th> 
                <th scope='row' id='your_score'> 0 </th> 
                <th scope='row' id='opponent_score'> 0 </th>
            </tr>

      </table>
      </div>
  </aside>
</main>
<div id='log-container'>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="scripts/dilemma.js"></script>
</body>
</html>