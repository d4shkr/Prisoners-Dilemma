$("#start_button").on("click", () => {
    $.post(
        "php_functions/create_game.php",
        {}, // send nothing to the script
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

document.getElementById("beb").addEventListener("click", () => {
    window.location.replace("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
})