// Functions that displays a link to join a game when the player clicks on Create Game button
$("#start_button").on("click", () => {
    $.post(
        "php_functions/create_game.php",
        // Game settings
        {
            "number_of_rounds": $("#rounds_range").val(), 
            "hide_rounds_num" : $("#hide_number_of_rounds_checkbox").prop("checked"),
            "both_cooperate_payoff" : $("#both_cooperate").val(), 
            "both_betray_payoff" : $("#both_betray").val(), 
            "was_betrayed_payoff" : $("#was_betrayed").val(), 
            "has_betrayed_payoff" : $("#has_betrayed").val()
        }, // send data to the script
        function (uuid) { // on response from POST

            // display link to join.php/?GameId=[uuid]
            const join_link = window.window.location.protocol + '//' + window.location.hostname + "/join.php?GameId=" + uuid;
            $("#join_link").text(join_link);

            // set href
            $("#join_link").attr("href", join_link);

            // make Join link visible
            $("#for-join-link").removeClass("hidden");
        }
    );
})

// Functions that displays a link to join a tournament when the player clicks on Create Tournament button
$("#start_tournament").on("click", () => {
    $.post(
        "php_functions/create_tournament.php",
        // Game settings
        {
            "number_of_rounds": $("#rounds_range").val(), 
            "hide_rounds_num" : $("#hide_number_of_rounds_checkbox").prop("checked"),
            "both_cooperate_payoff" : $("#both_cooperate").val(), 
            "both_betray_payoff" : $("#both_betray").val(), 
            "was_betrayed_payoff" : $("#was_betrayed").val(), 
            "has_betrayed_payoff" : $("#has_betrayed").val()
        }, // send data to the script
        function (uuid) { // on response from POST

            // display link to join.php/?GameId=[uuid]
            const join_link = window.window.location.protocol + '//' + window.location.hostname + "/join.php?TournamentId=" + uuid;
            $("#join_link").text(join_link);

            // set href
            $("#join_link").attr("href", join_link);

            // make Join link visible
            $("#for-join-link").removeClass("hidden");
        }
    );
})

var tournament = false;

$("#select_game").on("click", () => {
    $("#select_game").addClass("selected");
    $("#select_tournament").removeClass("selected");
    $("#tournament-settings").addClass("collapsed");
    tournament = false;
})

$("#select_tournament").on("click", () => {
    $("#select_game").removeClass("selected");
    $("#select_tournament").addClass("selected");
    $("#tournament-settings").removeClass("collapsed");
    tournament = true;
})

$("#create").on("click", () => {
    var display_link = function (tag, uuid) {
        const join_link = window.window.location.protocol + '//' + window.location.hostname + "/join.php?" + tag + "=" + uuid;
        $("#join_link").text(join_link);

        // set href
        $("#join_link").attr("href", join_link);

        // make Join link visible
        $("#for-join-link").removeClass("hidden");
    }
    if (tournament) {
        $.post(
            "php_functions/create_tournament.php",
            // Game and Tournament settings
            {
                "number_of_rounds": $("#rounds_range").val(), 
                "hide_rounds_num" : $("#hide_number_of_rounds_checkbox").prop("checked"),
                "both_cooperate_payoff" : $("#both_cooperate").val(), 
                "both_betray_payoff" : $("#both_betray").val(), 
                "was_betrayed_payoff" : $("#was_betrayed").val(), 
                "has_betrayed_payoff" : $("#has_betrayed").val(),
                "number_of_players" : $("#number_of_players").val(),
                "number_of_games" : $("#number_of_games_per_player").val()
            }, // send data to the script
            function (uuid) { // on response from POST
                display_link("TournamentId", uuid)
            }
        );
    } else {
        $.post(
        "php_functions/create_game.php",
        // Game settings
        {
            "number_of_rounds": $("#rounds_range").val(), 
            "hide_rounds_num" : $("#hide_number_of_rounds_checkbox").prop("checked"),
            "both_cooperate_payoff" : $("#both_cooperate").val(), 
            "both_betray_payoff" : $("#both_betray").val(), 
            "was_betrayed_payoff" : $("#was_betrayed").val(), 
            "has_betrayed_payoff" : $("#has_betrayed").val()
        }, // send data to the script
        function (uuid) { // on response from POST
            display_link("GameId", uuid)
        });
    }
})

function update_displayed_round_value() {
    const rounds = +$("#rounds_range").val();
    var dev = 0; 
    if ($("#hide_number_of_rounds_checkbox").prop("checked")) {
        dev = Math.floor(rounds / 2);
    }
    if (dev > 0) {
        $("#slider_output").text((rounds - dev) + ' - ' + (rounds + dev))
    } else {
        $("#slider_output").text(rounds)
    }
}
$("#rounds_range").on("input", update_displayed_round_value)
$("#hide_number_of_rounds_checkbox").on("input", update_displayed_round_value)
