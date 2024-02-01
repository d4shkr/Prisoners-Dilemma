<?php

class PayoffMatrix {
    /*
    *   Матрица выигрышей дилеммы заключенного
    */

    function __construct($C=-1, $B=-2, $c=0, $b=-3)
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