// This function updates Leaderboard
function update_leaderboard() { 
    $.post(
        "php_functions/get_leaderboard_data.php",
        {}, // send nothing to the script
        function (data) { // JSON on response from POST
            data = JSON.parse(data);
            const client_member_id = data["member_id"];
            var json = data["json"];
            const number_of_games = data["number_of_games"];

            // get all the member ids and sort them by total score descending
            var sorted_keys = Object.keys(json);
            sorted_keys.sort((a, b) => {return json[a][0].localeCompare(json[b][0])}); // First sort alphabetically (less priority)
            sorted_keys.sort((a, b) => {return json[a][1] - json[b][1]}); // Then by score (more priority)

            var leaderboard_str = `<div id='headers'>
                <div class='place'> Place </div>
                <div class='name'> Name </div>
                <div class='score'> Score </div>
                <div class='gamenum'> Games </div>
            </div>`;

            // concatenate rows for each member
            var place = 0;
            var last_place = 0;
            var last_score = -1;
            for (const member_id of sorted_keys) {
                place += 1;
                var score = +json[member_id][1];
                // if some members have the same score, they're put on the same place
                if (last_score != score) {
                    last_place = place;
                    last_score = score;
                }
                leaderboard_str += `<div class='row' id='${member_id}'>
                <div class='place'> ${last_place} </div>
                <div class='name'> ${json[member_id][0]} </div>
                <div class='score'> ${score} </div>
                <div class='gamenum'> ${json[member_id][2]} / ${number_of_games} </div>
            </div>`;
            }

            $("#leaderboard").html(leaderboard_str);
            $("#" + client_member_id).addClass('row-highlight');
        }
    );
}


// This function calls all the update functions
function update_all() {
    update_leaderboard();
}

// Update all website elements every second if needed
function loop_update_all() {
    update_all();
    setTimeout(loop_update_all, 1000);
}

update_all()
loop_update_all()


$("#nickname").on("keyup", (e) => {
    if (e.key === 'Enter' || e.keyCode === 13) {
        const name = $("#nickname").val();
        if (name.length < 2) {
            alert("Your name should be at least 2 symbols long.");
            return;
        }
        if (!$("#nickname").val().match(/^([\w ]{2,16})$/)) {
            alert("Your name shouldn consist only of latin letters, numbers, spaces and underscores.");
            return;
        }
        $.post (
            "php_functions/update_member_name.php",
            {"name": name}, // send name to the script
            function () { // JSON on response from POST
                update_all();
            }
        )
    }
})