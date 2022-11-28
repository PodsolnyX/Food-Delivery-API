<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function checkRatingDish($idDish) {

        $dish = query("SELECT * FROM dish WHERE idDish = '$idDish'");
        if ($dish == null) setHTTPStatus("404", "Dish not found");
        
        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query(
            "SELECT user.idUser FROM user 
            INNER JOIN dish_basket on user.idUser = dish_basket.idUser
            WHERE email = '$email' AND idDish = '$idDish'");

            $idUser = $resultUser["idUser"];

            if ($idUser != null) echo json_encode(true);
            else echo json_encode(false);
            setHTTPStatus("200");

        }
    }

?>