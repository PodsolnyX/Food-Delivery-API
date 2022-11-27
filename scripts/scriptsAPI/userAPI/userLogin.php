<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function loginUser($requestData) {

        if(!checkValidLoginData($requestData)) {
            responseIncorrectData();
            exit;
        }
        
        $link = connectToDataBase();

        $email = $requestData->body->email;

        $user = $link->query("SELECT email, password FROM user WHERE email = '$email'")->fetch_assoc();

        if (is_null($user)) {
            responseIncorrectPasswordOrLogin();
        }
        else {
            if (hash("sha1", $requestData->body->password) == $user["password"]) {
                $response = [
                    "token" => generateToken($email)
                ];
                echo json_encode($response);
                http_response_code(200);
            }
            else {
                responseIncorrectPasswordOrLogin();
            }
        }
    }

    function checkValidLoginData($requestData) {

        if (strlen($requestData->body->password) < 6) {
            return false;
        }
        else if (!filter_var($requestData->body->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

?>