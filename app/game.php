<?php

class PayoffMatrix {
    /*
    *   Матрица выигрышей дилеммы заключенного
    */

    function __construct($C=1, $B=2, $c=0, $b=3)
    {
        // C - payoff if both cooperate
        // B - payoff if both betray
        // c - payoff of the one who cooperates
        // b - payoff of the one who betrays

        $this->table = array($C, $B, $c, $b);
    }

    function get_html_table() 
    {
        // Создаем HTML таблицу вида:
        /*
        |            |    Молчать   |     Сдать      |
        |  Молчать   |    (-1,-1)   |    (-3, 0)     |
        |  Сдать     |    (0, -3)   |    (-2,-2)     |
        */
        $result = "<table id='payoff'> 
            <caption> Payoff matrix </caption>
            <tr> 
                <th scope='col'> Pl1, Pl2 </th>
                <th scope='col'> Cooperate </th>  
                <th scope='col'> Betray </th>  
            </tr>
            <tr> 
                <th scope='row'> Cooperate </th> 
                <td> {$this->table[0]}, {$this->table[0]}</td> 
                <td> {$this->table[2]}, {$this->table[3]}</td>
            </tr>
            <tr> 
                <th scope='row'> Betray </th> 
                <td> {$this->table[3]}, {$this->table[2]}</td> 
                <td> {$this->table[1]}, {$this->table[1]}</td> 
            </tr>
        </table>";

        return $result;

    }
}


class Score {
    /*
    *   Таблица с текущим счётом
    */

    // Массивы с результатами каждого хода для каждого игрока
    public $player1_rounds;
    public $player2_rounds;

    // Отдельно храним накопленные очки
    public $player1_score;
    public $player2_score;

    // Имена игроков
    public $player1 = "Player 1";
    public $player2 = "Player 2";

    function __construct() {
        $this->player1_score = 0;
        $this->player2_score = 0;
        $this->player1_rounds = array();
        $this->player2_rounds = array();
    }

    function round($player1_points, $player2_points) // передаем пару очков, которые получили игроки в данном раунде
    {
        /*
        *   Начисление очков после раунда
        */ 

        // добавляем в массивы текущие очки
        array_push($this->player1_rounds, $player1_points);
        array_push($this->player2_rounds, $player2_points);
        // обновляем накоп
        $this->player1_score += $player1_points;
        $this->player2_score += $player2_points;
    }

    function get_html_table() 
    {
        // Создаем HTML таблицу вида:
        /*
        |           |   Player1  |   Player2  |
        |    Sum    |     -3     |     -3     |
        |   Round1  |     -1     |     -1     |
        |   Round2  |     -2     |     -2     |
        */

        // Первые две строки таблицы
        $result = "<table id='score'>
        <caption> Score </caption>
            <tr> 
                <th scope='col'>  </th>
                <th scope='col'> {$this->player1} </th>  
                <th scope='col'> {$this->player2} </th>  
            </tr>
            <tr> 
                <th scope='row'> Sum </th> 
                <th scope='row'> {$this->player1_score} </td> 
                <th scope='row'> {$this->player2_score} </td>
            </tr>";

        // Записываем раунды
        for ($i = 0; $i != count($this->player1_rounds); $i++) {
            $round = "Round" . strval($i + 1);
            $result .= "<tr>
                <td> {$round} </td> <td> {$this->player1_rounds[$i]} </td> <td> {$this->player2_rounds[$i]} </td>
            </tr>";
        }
        $result .= "</table>";

        return $result;
    }

}

class ActionButtons 
{
    /*
    *   Кнопки "Молчать", "Сдать"
    */
    function get_html($link_cooperate, $link_betray) { // передаем ссылку для каждой кнопки
        $result = "<div id='action_buttons'>
        <a href='?{$link_cooperate}' class=Hidden>
        <div class='button' id='cooperate'>
            <p> Молчать </p>
        </div> </a>
        <a href='?{$link_betray}' class=Hidden>
        <div class='button' id='betray'>
            <p> Сдать </p>
        </div> </a>
        </div>";
        return $result;
    }
}

// class Game
// class Master


// Как может выглядеть SQL-таблица
// Если по id игроков две таблицы:
/* 
    Создать класс Game, объект которого содержит всю информацию об игре: таблицу с раундами Score и PayoffMatrix 
    И два массива выборов ( Player i choices ) и номер текущего раунда (=размер массива Player 1 choices)

    (этот объект можно будет сунуть в json с помощью json_encode)


*/

// { game_id : [player1_id, player2_id, [player1_score_cumulative], [player2_score_cumulative], player1_current_choice, player2_current_choice]} // choices

// Две таблицы
// 1-я: id Игры и json объекта класса Game => ('gameid', 'json')
// 2-я для игроков: id игрока в конкретной игре (по нему будем доставать пару [game id, номер игрока]) => ('playerid', {gameid: ..., player: 1 or 2})

// 2-я таблица нужна, чтобы не хранить номер игрока по ссылке чтобы его нельзя было поменять
// По ссылке будет передаваться 'playerid' и 'choice' и номер раунда (с помощью номера раунда можно проверять что игрок на нужном раунде)
// по playerid будет доставаться номер игрока (1 or 2) и gameid, а по gameid уже можно будет достать всё состояние игры

// внутри формы тег input type="hidden" номер
// <form action = "action" method="POST">
//      <input name = ...>

// if isset($_POST['action']): проверять поле с значением нажатой кнопки и скрытое поле с номером игрока двумя ифами


// другой способ: $_SESSION для подсчета количества пользователей