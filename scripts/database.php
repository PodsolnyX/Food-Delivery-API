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

    function query($query, $fetchFlag = true) {
        global $link;

        $result = $link->query($query);

        if (!$result) {
            setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
            exit;
        }

        if ($fetchFlag == true) {
            $result = $result->fetch_assoc();
        }

        return $result;
    }

?>