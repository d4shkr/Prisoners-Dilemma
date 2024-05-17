<?php
include_once('game.php');
include_once('db.php');

if (!isset($_COOKIE['PlayerId'])) {

  echo "<div class='error'> Player Id is incorrect </div>";
  exit;
}

session_start();
$_SESSION['PlayerId'] = $_COOKIE['PlayerId']; // if something goes wrong or the player decides to clear cookies during the game

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Play Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/dilemma.css">
  </head>

  <body>
    <!-- Navigation bar -->
    <nav>
    <div id='for-round'> <div id='round'> <h2> Round 1 </h2> </div> </div>
      <ul>
        <li><a id='about'> About </a> </li>
        <li><a id='home-link'> Home </a> </li>
        <li><a id='edit-login-link'> Player </a> </li>
      </ul>
    </nav>
    <!-- Game page -->
    <main>
      <article>
        <p> 
          You will play a prisoner's dilemma game repeatedly. 
          In this game, you and another player must decide simultaneously either to "cooperate" or to "betray". 
        </p>

      <!-- Payoff Matrix -->
      <!--
        <p> Corresponding payoffs are determined as follows: For one shot of the game, if both players compete, they both get a payoff equal to -2.
          If both cooperate, they both get -1. If one cooperates and the other competes, the first one gets -3 and the second gets 0. </p>
        -->
      <table id='payoff'> 
            <caption> Payoff matrix </caption>
            <tr> 
                <th scope='col'> Pl1, Pl2 </th>
                <th scope='col'> Cooperate </th>  
                <th scope='col'> Betray </th>  
            </tr>
            <tr> 
                <th scope='row'> Cooperate </th> 
                <td> -1, -1</td> 
                <td> -3, 0 </td>
            </tr>
            <tr> 
                <th scope='row'> Betray </th> 
                <td> 0, -3</td> 
                <td> -2, -2</td> 
            </tr>
        </table>

        
      <p> Do you want to cooperate or to betray? Please make your next move. </p>
      <!-- Action Buttons -->
      <div id='action_buttons'>
        <div class='button' id='cooperate'>
            <p> Cooperate </p>
        </div>
        <div class='button' id='betray'>
            <p> Betray </p>
        </div>
      </div>

      <!-- Waiting message -->
      <div class='hidden for-waiting-message' id='waiting'>
        Waiting for other player to choose...
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
<footer>
      <p>©Copyright 2024 by dasha. All rights reversed.</p>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="scripts/dilemma.js"></script>
</body>
</html>