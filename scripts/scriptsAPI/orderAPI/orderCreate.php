<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function createOrder($requestData) {

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];

            $resultUser = $link->query(
                "SELECT user.idUser FROM user 
                WHERE email = '$email'")->fetch_assoc();

            $currentUser = $resultUser["idUser"];
            $idOder = uniqid();
            $orderPrice = 0;
            $orderTime = new DateTime();
            $orderTime = str_replace("T", " ", substr(gmdate(DATE_ATOM, $orderTime->getTimestamp()), 0, 19));
            $deliveryTime = str_replace("T", " ", substr($requestData->body->deliveryTime, 0, 19));
            $address = $requestData->body->address;

            if ($orderTime >= $deliveryTime) {
                $response = [
                    "status" => '401',
                    "message" => 'Время заказа позже, чем время доставки'
                ];
                echo json_encode($response);
                exit; 
            }

            $resultBasket = $link->query(
                "SELECT price, amount FROM dish_basket
                INNER JOIN dish on dish_basket.idDish = dish.idDish
                WHERE idUser = '$currentUser' AND idOrder IS NULL"
            );
    
            while ($row = mysqli_fetch_assoc($resultBasket)) {
                $orderPrice += $row["price"]*$row["amount"];
            };

            if ($orderPrice == 0) {
                $response = [
                    "status" => '404',
                    "message" => 'Корзина пуста'
                ];
                echo json_encode($response);
                exit;  
            }

            $result = $link->query(
                "INSERT `order`(idOrder, idUser, deliveryTime, orderTime, status, price, address) 
                VALUES ('$idOder', '$currentUser', '$deliveryTime', '$orderTime', 'InProcess', '$orderPrice', '$address')"
            );

            $result = $link->query("UPDATE dish_basket SET idOrder = '$idOder' WHERE idOrder IS NULL");
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
