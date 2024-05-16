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

            $("#score").html(table_str);
        }
    );
}

update_scoretable()