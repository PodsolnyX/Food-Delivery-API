<?php
    function connectToDataBase() {
        $link = mysqli_connect("127.0.0.1", "backend", "password", "deliveryFood");

        if (!$link) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Код ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        else {
            return $link; 
        }
    }
?>