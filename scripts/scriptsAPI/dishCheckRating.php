<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function checkRatingDish($idDish) {

        $link = connectToDataBase();

        $dish = $link->query("SELECT * FROM dish WHERE idDish = '$idDish'")->fetch_assoc();

        if ($dish == null) {
            $response = [
                "status" => '404',
                "message" => 'Dish not found'
            ];
            echo json_encode($response);
            exit;
        }

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];

            $resultUser = $link->query(
            "SELECT user.idUser FROM user 
            INNER JOIN dish_basket on user.idUser = dish_basket.idUser
            WHERE email = '$email' AND idDish = '$idDish'")->fetch_assoc();

            $idUser = $resultUser["idUser"];

            if ($idUser != null) {
                echo json_encode(true);
            }
            else {
                echo json_encode(false);
            }

        }
        else {
            $response = [
                "status" => '401',
                "message" => 'Unauthorized'
            ];
            echo json_encode($response);
            exit;
        }
    }

?>