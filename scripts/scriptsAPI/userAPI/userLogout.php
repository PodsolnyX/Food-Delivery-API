<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function logoutUser() {

        $token = getTokenFromHeader();

        if (isTokenValid($token)) {
            findUserIDByToken($token);
            query("INSERT INTO expired_token VALUES ('$token')", false);
            setHTTPStatus("200");
        }
    }

?>