$("#start_button").on("click", () => {
    $.post(
        "php_functions/create_game.php",
        {}, // send nothing to the script
        function (uuid) {

            // display link to join.php/?GameId=[uuid]
            let join_link = window.location.pathname;
            let i = join_link.lastIndexOf('/');
            join_link = join_link.substring(0, i + 1);
            join_link += "join.php/?GameId=" + uuid;
            
            $("#join_link").text(join_link);
        }
    );
})

document.getElementById("beb").addEventListener("click", () => {
    window.location.replace("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
})