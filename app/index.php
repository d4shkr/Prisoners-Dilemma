<?php
  include_once('game.php');
  $payoffs = new PayoffMatrix();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Prisoner's dilemma</title>
    <style>
      .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
      }
    </style>
  </head>

  <body>
    <?php
      $table = $payoffs->get_html_table();
      echo $table;
    ?>
  </body>
</html>