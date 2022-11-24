<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function logoutUser() {
        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);
        
        if ($token == null) {
            responseBadRequest();
            exit;
        }

        $link->query("INSERT INTO expired_token VALUES ('$token')");
        http_response_code(200);
    }

?>