<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function checkRatingDish($idDish) {

        checkDishExists($idDish);
        
        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $result = query(
                "SELECT id FROM dish_basket 
                WHERE idUser = '$idUser' AND idDish = '$idDish' AND idOrder IS NOT NULL"
            );

            if ($result != null) echo json_encode(true);
            else echo json_encode(false);
            setHTTPStatus("200");

        }
    }

?>