<?php

    include_once 'scripts/headers.php';

    function connectToDataBase() {
        $link = mysqli_connect("127.0.0.1", "backend", "password", "deliveryFood");

        if (!$link) {
            setHTTPStatus("500", "DB Connection error: " . mysqli_connect_error());
            exit;
        }
        else {
            return $link; 
        }
    }
?>