<?php

class PayoffMatrix {
    /*
    * Матрица выигрышей дилеммы заключенного
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
        $result = "<table> 
        <caption> Payoff matrix </caption>
        <tr> <th scope='col'> Pl1, Pl2 </th><th scope='col'> Cooperate </th>  <th scope='col'> Betray </th>  </tr>
        <tr> <th scope='row'> Cooperate </th> <td> {$this->table[0]}, {$this->table[0]}</td> <td> {$this->table[2]}, {$this->table[3]}</td> </tr>
        <tr> <th scope='row'> Betray </th> <td> {$this->table[3]}, {$this->table[2]}</td> <td> {$this->table[1]}, {$this->table[1]}</td> </tr>
        </table>";

        return $result;

    }
}


class Score {
    /*
    * Таблица с текущим счётом
    */

    public $n; // Количество ходов
    public $data; // Таблица с результатами каждого хода
    public $payoff_matrix; // Матрица выигрышей
    public $current_player; // Игрок, который должен сделать текущий ход

    const PLAYER1 = "Player 1";
    const PLAYER2 = "Player 2";

    function __construct($n) {
        $this->data = array();
        $this->data[0][0] = PLAYER1;
        $this->data[0][1] = PLAYER2;
    }
}