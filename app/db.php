<?php

$link = mysqli_connect('mysql', 'root', 'root', 'db');
if (!$link) {
    die('Ошибка соединения: ' . mysqli_error());
}
?>