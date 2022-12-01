<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function logoutUser() {

        $token = getTokenFromHeader();

        if (isTokenValid($token)) {
            findUserIDByToken($token);
            query("INSERT INTO expired_token VALUES ('$token')", false);
            setHTTPStatus("200");
        }
    }

?>