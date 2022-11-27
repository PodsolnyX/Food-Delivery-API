<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function deleteDishFromBasket($idDish) {

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
                WHERE email = '$email'")->fetch_assoc();

            $currentUser = $resultUser["idUser"];

            $resultBasketDish = $link->query(
            "SELECT id, amount FROM dish_basket
            WHERE idUser = '$currentUser' AND idDish = '$idDish' AND idOrder IS NULL")->fetch_assoc();

            $idBasketDish = $resultBasketDish["id"];

            if ($idBasketDish == null) {
                $response = [
                    "status" => '404',
                    "message" => 'Данный товар отсутствует в корзине'
                ];
                echo json_encode($response);
                exit;                  
            }
            else {
                $amountBasketDish = $resultBasketDish["amount"] - 1;
                
                if ($amountBasketDish == 0) {
                    $result = $link->query(
                        "DELETE FROM dish_basket WHERE id = '$idBasketDish'");
                    echo json_encode($link->error);
                    http_response_code(200);
                }
                else {
                    $result = $link->query(
                        "UPDATE dish_basket SET amount = '$amountBasketDish' 
                        WHERE id = '$idBasketDish'");
                    echo json_encode($link->error);
                    http_response_code(200);
                }
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
