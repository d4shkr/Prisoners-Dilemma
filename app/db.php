<?php

$link = mysqli_connect('mysql', 'root', 'root', 'demo');
if (!$link) {
    die('Ошибка соединения: ' . mysqli_error());
}
?>