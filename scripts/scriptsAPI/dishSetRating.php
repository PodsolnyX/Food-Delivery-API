<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function setRatingDish($idDish, $ratingScore) {

        if ($ratingScore < 0 || $ratingScore > 10) {
            $response = [
                "status" => '401',
                "message" => 'Incorrect rating score'
            ];
            echo json_encode($response);
            exit;
        }

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

                $resultRating = $link->query(
                "SELECT rating.rating FROM rating 
                WHERE idUser = '$idUser' AND idDish = '$idDish'")->fetch_assoc();

                $currentRating = $resultRating["rating"];

                if ($currentRating == null) {
                    $result = $link->query(
                        "INSERT INTO rating(idUser, idDish, rating) 
                        VALUES ('$idUser', '$idDish', '$ratingScore')");
                    echo json_encode($link->error);
                    http_response_code(200);
                }
                else {
                    $result = $link->query(
                        "UPDATE rating SET rating = '$ratingScore' 
                        WHERE idUser = '$idUser' AND idDish = '$idDish'");
                    echo json_encode($link->error);
                    http_response_code(200);
                }
            }
            else {
                $response = [
                    "status" => '404',
                    "message" => 'Пользователь не покупал этот товар'
                ];
                echo json_encode($response);
                exit;
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