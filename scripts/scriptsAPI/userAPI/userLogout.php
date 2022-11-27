<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function logoutUser() {
        global $link;

        $token = substr(getallheaders()['Authorization'], 7);

        if ($token == null) {
            setHTTPStatus("400", "Token is null");
            exit;
        }

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'");

        if (!$result) {
            setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
            exit;
        }

        $result = $result->fetch_assoc();

        if (!isExpired($token) && isValid($token) && $result == null) {
            $result = $link->query("INSERT INTO expired_token VALUES ('$token')");

            if (!$result) {
                setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
                exit;
            }
            
            setHTTPStatus("200");
        }
        else if (!isValid($token)) {
            setHTTPStatus("403");
        }
        else if ($result != null) {
            setHTTPStatus("401");
        }    
    }

?>