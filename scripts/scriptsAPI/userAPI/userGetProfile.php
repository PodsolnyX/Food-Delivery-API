<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function getProfileUser() {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];
            $user = query("SELECT * FROM user WHERE email = '$email'");

            if (is_null($user)) setHTTPStatus("403");
            
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
            setHTTPStatus("200");
        }
    }

?>