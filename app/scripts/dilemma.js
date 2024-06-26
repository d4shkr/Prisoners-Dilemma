// Save animation time to let it finish
var last_animation_time = 0;
const animation_duration = 500;
// This function updates Score table on dilemma.php page with data from the database
function update_scoretable() {
    // if animation is still playing, don't update the table
    if (last_animation_time + animation_duration > Date.now()) {
        return;
    }
    $.post(
        "php_functions/get_scoretable_data.php",
        {}, // send nothing to the script
        function (data) { // on response from POST
            data = data.split('|'); // format: "YourScore|OpponentScore|YourPayoffHistory|OpponentPayoffHistory"
            const your_score = data[0];
            const opponent_score = data[1];
            const your_payoff_history = JSON.parse(data[2]);
            const opponent_payoff_history = JSON.parse(data[3]);

            const old_round_count = $('#score tr').length - 2;

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
            for (var i = your_payoff_history.length - 1; i >= 0; --i) {
                table_str += `<tr>\
                    <td> Round ${i + 1} </td> <td> ${your_payoff_history[i]} </td> <td> ${opponent_payoff_history[i]} </td>\
                </tr>`
            }

            // insert html code for the score table
            $("#score").html(table_str);

            // if table has changed, play the animation
            if (your_payoff_history.length > old_round_count) {
                $("#score tr:nth-child(3)").addClass("light-up");
                last_animation_time = Date.now()
            }
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
                // if the player didn't choose yet: display the action buttons, hide the waiting message and the loader
                case 'unknown':
                    $("#action_buttons").removeClass("collapsed");
                    $("#waiting").addClass("collapsed");
                    $("#loader").addClass("collapsed");
                    $("#tournament-link").addClass("collapsed");
                    break;
                // if the player chose to betray: hide the action buttons, update and display the waiting message and the loader
                case 'betrayed':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("You chose to <span style='color: #fb89b7'>betray</span>. Waiting for the opponent...");
                    $("#waiting").removeClass("collapsed");
                    $("#loader").removeClass("collapsed");
                    $("#tournament-link").addClass("collapsed");
                    break;
                // if the player chose to cooperate: hide the action buttons, update and display the waiting message and the loader
                case 'cooperated':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("You chose to <span style='color: #7dca6e'>cooperate</span>. Waiting for the opponent...");
                    $("#waiting").removeClass("collapsed");
                    $("#loader").removeClass("collapsed");
                    $("#tournament-link").addClass("collapsed");
                    break;
                // if the game is finished: hide the action buttons and notify the player
                case 'finished':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").html("Game Over... bye-bye!");
                    $("#waiting").removeClass("collapsed");
                    $("#loader").addClass("collapsed");
                    $("#tournament-link").addClass("collapsed");
                    break;
                case 'tournament':
                    $("#action_buttons").addClass("collapsed");
                    $("#waiting").addClass("collapsed");
                    $("#loader").addClass("collapsed");
                    $("#tournament-link").removeClass("collapsed");
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
        "php_functions/recieve_player_message.php",
        {}, // send nothing to the script
        function (message) { // on response from POST
            if (!message) {
                return;
            }

            $("#log-container").prepend(`<footer class="log-footer"> <p>${message}</p> </footer>`);
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

// Update all website elements every second if needed
function loop_update_all() {
    $.post(
        "php_functions/get_player_up_to_date.php",
        {}, // send nothing to the script
        function (message) { // on response from POST
            if (message == '0') {
                update_all(); // update if the visuals are outdated
            }
        }
    )
    setTimeout(loop_update_all, 1000);
}

update_all()
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

// This function displays the Guide Window when we click the Guide button in the navigation bar
$("#guide").on("click", () => {
    $("#guide-page").removeClass("no-display");
})

// Hide the Guide Window when we click away from it
$("#guide-page").on("click", () => {
    $("#guide-page").addClass("no-display");
})
