<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function loginUser($requestData) {

        checkValidLoginData($requestData);

        $email = $requestData->body->email;

        $user = query("SELECT email, password FROM user WHERE email = '$email'");

        if (is_null($user)) setHTTPStatus("400", "Incorrect password or login");
        else {
            if (hash("sha1", $requestData->body->password) == $user["password"]) {
                echo json_encode(["token" => generateToken($email)]);
                setHTTPStatus("200");
            }
            else setHTTPStatus("400", "Incorrect password or login");
        }
    }

    function checkValidLoginData($requestData) {

        if (strlen($requestData->body->password) < 6) {
            setHTTPStatus("400", "Password length is less than 6");
        }
        else if (!filter_var($requestData->body->email, FILTER_VALIDATE_EMAIL)) {
            setHTTPStatus("400", "Inccorect login");
        }
    }

?>