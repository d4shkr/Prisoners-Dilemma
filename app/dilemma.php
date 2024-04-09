<?php
  include_once('game.php');
  $payoffs = new PayoffMatrix();
  $score = new Score();
  $action_buttons = new ActionButtons();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Play Prisoner's dilemma</title>
    <link rel="stylesheet" href="css/style_dilemma.css">
  </head>

  <body>
    <?php
      // test PayoffMatrix class
      $table = $payoffs->get_html_table();
      echo $table;
      // test Score class
      $score->round(1, 1);
      $score->round(2, 2);
      $score_table = $score->get_html_table();
      echo $score_table;
      // test ActionButtons class
      $buttons = $action_buttons->get_html('', ''); # пока передаю пустые ссылки
      echo $buttons;
    ?>
  </body>
</html>