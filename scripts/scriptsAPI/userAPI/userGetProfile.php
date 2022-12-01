<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/database.php';
    include_once 'scripts/helpers/JWT.php';

    function getProfileUser() {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $user = query("SELECT * FROM user WHERE idUser = '$idUser'");
            
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