<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0" />
    <title>Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/payoff.css">
    <link rel="stylesheet" href="css/index.css">
    
</head>
<body>
    <!-- Navigation bar -->
    <nav>
    <div id='for-header'> <div id='header'> <h2> Prisoner's Dilemma </h2> </div> </div>
      <ul>
        <li><a id='guide'> Guide </a> </li>
        <li><a id='about'> About </a> </li>
      </ul>
    </nav>

    <!-- About Window -->
    <div id='about-page' class='dim no-display'>
        <div class='window popout'> 
            <p> This is a course project aimed at creating a customizable multiplayer iterated Prisoner's Dilemma game. </p> 
            <p> Source code can be found on <a href='https://github.com/d4shkr/Prisoners-Dilemma'>github</a>. </p>
        </div>
    </div>

    <!-- Guide -->
    <div id='guide-page' class='dim no-display'>
        <div class='window popout'>
            <p> You can either create a <b style='color:#EDADC7'>Quick Game</b> for two people or a <b style='color:#EDADC7'>Tournament</b> where everyone will play the same number of games against each other. </p> 
            <p> In the <b style='color:#912F56'>Game Settings</b> section you can configure the rules of the iterated Prisoner's Dilemma. </p>
            <p>In the <b style='color: #912F56'>Tournament Settings</b> section you can also choose the number of players in the tournament and how many games each of them will play.</p>
            <p> After <b style='color:#EDADC7'>creating</b> a game or a tournament, you can <b style='color:#EDADC7'>copy</b> the <b style='color: #0000EE'>invite link</b> and send it to your friends :) </p>
        </div>
    </div>

    <main>

    <div class='for-button'>
        <div class='button selected' id='select_game'> <p> Quick Game </p> </div>
        <div class='button' id='select_tournament'> <p> Tournament </p> </div>
    </div>


    <div id='settings-container'>
        <!-- Choose the number of rounds -->
        <h3> Game Settings </h3>
        <div class='settings'>
            <p> Number of rounds: <span id='slider_output'> 5 </span> </p> 
            <!-- Numbers above the slider -->
             <!--
            <div class="container space-between">
                <div>1</div>
                <div>15</div>
                <div>30</div>
            </div> -->
            <div class="slidecontainer">
                <input type="range" min="1" max="30" value="5" class="slider" id="rounds_range">
            </div>
        </div>
        <!-- Hide the numbr of rounds -->
        <div class='settings checkbox'>
            <label><input id="hide_number_of_rounds_checkbox" type="checkbox"><span> Hide the number of rounds </span> </label>
        </div>
        <!-- Payoffs -->
        <table id='payoff'> 
            <caption> Your Payoff matrix </caption>
            <tr> 
                <th scope='col'> You \ Opp </th>
                <th scope='col'> Cooperate </th>  
                <th scope='col'> Betray </th>  
            </tr>
            <tr> 
                <th scope='row'> Cooperate </th> 
                <td> <input type="number" id="both_cooperate" value="3" step="1"></td> 
                <td> <input type="number" id="was_betrayed" value="0" step="1"></td>
            </tr>
            <tr> 
                <th scope='row'> Betray </th> 
                <td> <input type="number" id="has_betrayed" value="5" step="1"></td> 
                <td> <input type="number" id="both_betray" value="1" step="1"></td> 
            </tr>
        </table>
        <div id = "tournament-settings" class="collapsed">
            <h3 style="margin-bottom: 0"> Tournament Settings </h3>
            <div class = "for-tournament-number-settings">
                <div class = "tournament-number-setting">
                    <p> <b>Number of players</b> </p>
                    <input type = "number" id="number_of_players" value="4" min="3" max="99">
                </div>
                <div class = "tournament-number-setting">
                    <p> <b>Games per player</b> </p>
                    <input type = "number" id="number_of_games_per_player" value="3" min="1" max="99">
                </div>
            </div>
        </div>
    </div>

    <div class="for-button">
        <div class='button' id='create'> <p> Create </p> </div>
    </div>

    <div class='hidden for-join-link' id='for-join-link'>
        <div id='invite'> <p> Invite link: </p></div>
        <a id='join_link' target='_blank'> </a> <br>
        <div id='copy-link-button' class='button'> Copy </div>
    </div>
</main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts/index.js"></script>
</body>
</html>