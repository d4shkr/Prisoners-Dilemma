// This function updates Score table on dilemma.php page with data from the database
function update_scoretable() { 
    $.post(
        "php_functions/get_scoretable_data.php",
        {}, // send nothing to the script
        function (data) { // on response from POST
            data = data.split('|'); // format: "YourScore|OpponentScore|YourPayoffHistory|OpponentPayoffHistory"
            const your_score = data[0];
            const opponent_score = data[1];
            const your_payoff_history = JSON.parse(data[2]);
            const opponent_payoff_history = JSON.parse(data[3]);

            var table_str = `<caption> Score </caption>\
            <tr>\
                <th scope='col'>  </th>\
                <th scope='col'> You </th>\
                <th scope='col'> Opponent </th>\
            </tr>\
            <tr>\
                <th scope='row'> Score </th>\
                <th scope='row' id='your_score'> ${your_score} </th>\
                <th scope='row' id='opponent_score'> ${opponent_score} </th>\
            </tr>`

            // concatenate table rows for each round
            for (var i = 0; i < your_payoff_history.length; ++i) {
                table_str += `<tr>\
                    <td> Round ${i + 1} </td> <td> ${your_payoff_history[i]} </td> <td> ${opponent_payoff_history[i]} </td>\
                </tr>`
            }

            // insert html code for the score table
            $("#score").html(table_str);
        }
    );
}

// This function updates button area based on the player's choice
function update_button_area() {
    $.post(
        "php_functions/get_player_choice.php",
        {}, // send nothing to the script
        function (choice) { // on response from POST
            switch (choice) {
                // if the player didn't choose yet: display the action buttons, hide the waiting message
                case 'unknown':
                    $("#action_buttons").removeClass("collapsed");
                    $("#waiting").addClass("collapsed");
                    break;
                // if the player chose to betray: hide the action buttons, update and display the waiting message
                case 'betrayed':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("You chose to betray. Waiting for other player...");
                    $("#waiting").removeClass("collapsed");
                    break;
                // if the player chose to cooperate: hide the action buttons, update and display the waiting message
                case 'cooperated':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("You chose to cooperate. Waiting for other player...");
                    $("#waiting").removeClass("collapsed");
                    break;
                // if the game is finished: hide the action buttons and notify the player
                case 'finished':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("Game Over... bye-bye!");
                    $("#waiting").removeClass("collapsed");
            }
        }
    )
}

// This function updates the round number in the navigation bar
function update_round() {
    $.post(
        "php_functions/get_round.php",
        {}, // send nothing to the script
        function (round) { // on response from POST
            if (round != 'finished') {
                $("#round").html(`<h2> Round ${round} </h2>`);
            } else {
                $("#round").html(`<h2> Game Over </h2>`);
            }
        }
    )
}

// This function tries to get a message from the server and displays it, if there is one
function display_message() {
    $.post(
        "php_functions/get_player_message.php",
        {}, // send nothing to the script
        function (message) { // on response from POST
            if (!message) {
                return;
            }

            alert(message); // TODO: THIS IS TEMPORARY!!
        }
    )
}

// This function calls all the update functions
function update_all() {
    update_button_area();
    update_scoretable();
    update_round();
    display_message();
}

// Update all website elements every second
function loop_update_all() {
    update_all();
    setTimeout(loop_update_all, 1000);
}

loop_update_all()

// when the player clicks "Cooperate" button:
$("#cooperate").on("click", () => {
    $.post(
        "php_functions/choose_cooperate.php",
        {}, // send nothing to the script
        function () { 
            // Game state has changed (probably). Update visuals accordingly.
            update_all();
        }
    );
})

// when the player clicks "Betray" button:
$("#betray").on("click", () => {
    $.post(
        "php_functions/choose_betray.php",
        {}, // send nothing to the script
        function () { 
            // Game state has changed (probably). Update visuals accordingly.
            update_all();
        }
    );
})

