<?php

$link = mysqli_connect('mysql', 'root', 'root');
if (!$link) {
    die('Ошибка соединения: ' . mysqli_error());
}

mysqli_close($link);
?>