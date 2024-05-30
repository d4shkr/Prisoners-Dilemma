<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0" />
    <title>Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/index.css">
    
</head>
<body>
    <!-- Navigation bar -->
    <nav>
    <div id='for-header'> <div id='header'> <h2> Prisoner's Dilemma </h2> </div> </div>
      <ul>
        <li><a id='about'> About </a> </li>
        <li><a id='home-link'> Home </a> </li>
        <li><a id='edit-login-link'> Player </a> </li>
      </ul>
    </nav>

    <!--
    <header>

    <div class="main-heading">
        <h2><span id="Prisoner's Dilemma">Prisoner's Dilemma</span></h2>
        <p>This is a round-based multiplayer Prisoner's Dilemma Game</p>
    </div>  
    </header>
    -->
    <main>
    <div class='for-button'>
        <div class='button' id='start_button'> <p> Create Game </p> </div>
    </div>

    <div class='for-button'>
        <div class='button' id='start_tournament'> <p> Create Tournament </p> </div>
    </div>

    <div class='for-button'> 
        <div class='button' id='beb'> <p> Тык </p></div> 
    </div>

    <div class='hidden for-join-link' id='for-join-link'>
        <div class='button' id='invite'> <p> Invite link: </p></div>
        <a id='join_link' target='_blank'> </a>
    </div>


    <div id='settings'>
        <!-- Choose the number of rounds -->
        <div class='settings'>
            <p> Number of rounds: <span id='slider_output'> 5 </span> </p> 
            <!-- Numbers above the slider -->
            <div class="container space-between">
                <div>1</div>
                <div>15</div>
                <div>30</div>
            </div>
            <div class="slidecontainer">
                <input type="range" min="1" max="30" value="5" class="slider" id="rounds_range">
            </div>
        </div>
        <!-- Hide the numbr of rounds -->
        <div class='settings'>
            <span> Hide the number of rounds: </span>
            <input id="hide_number_of_rounds_checkbox" type="checkbox">
        </div>
        <!-- Payoffs -->
        <div class='settings'>
            <span> If you and your opponent cooperate: </span>
            <input type="number" id="both_cooperate" value="3">
            <br>
            <span> If you and your opponent betray: </span>
            <input type="number" id="both_betray" value="1">
            <br>
            <span> If you betray and your opponent cooperates: </span>
            <input type="number" id="has_betrayed" value="5">
            <br>
            <span> If you cooperate and your opponent betrays: </span>
            <input type="number" id="was_betrayed" value="0">
        </div>
    </div>
</main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts/index.js"></script>
</body>
</html>