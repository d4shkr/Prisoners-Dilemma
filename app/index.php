<?php
  include_once('game.php');
  $payoffs = new PayoffMatrix();
  $score = new Score();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Prisoner's dilemma</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <?php
      // test PayoffMatrix class
      $table = $payoffs->get_html_table();
      echo $table;
      // test Score class
      $score->round(-1, -1);
      $score->round(-2, -2);
      $score_table = $score->get_html_table();
      echo $score_table;
    ?>
  </body>
</html>