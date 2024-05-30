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

$("#rounds_range").on("input", () => {
    $("#slider_output").text($("#rounds_range").val()) // gets the oninput value
})

document.getElementById("beb").addEventListener("click", () => {
    window.location.replace("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
})