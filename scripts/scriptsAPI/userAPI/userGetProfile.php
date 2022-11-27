<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function getProfileUser() {
        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];
            $user = $link->query("SELECT * FROM user WHERE email = '$email'")->fetch_assoc();

            $userData = [
                "id" => $user["idUser"],
                "fullName" => $user["fullName"],
                "address" => $user["address"],
                "birthDate" => $user["birthDate"] . "T00:00:00.000Z",
                "gender" => $user["gender"],
                "email" => $user["email"],
                "phoneNumber" => $user["phoneNumber"]
            ];

            echo json_encode($userData);
            http_response_code(200);
        }
        else {
            responseUnauthorized();
            exit;
        }
    }

?>