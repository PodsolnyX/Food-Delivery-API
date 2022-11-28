<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function createOrder($requestData) {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query("SELECT user.idUser FROM user WHERE email = '$email'");
            $currentUser = $resultUser["idUser"];

            $idOder = uniqid();
            $orderPrice = 0;
            $orderTime = new DateTime();
            $orderTime = str_replace("T", " ", substr(gmdate(DATE_ATOM, $orderTime->getTimestamp()), 0, 19));
            $deliveryTime = str_replace("T", " ", substr($requestData->body->deliveryTime, 0, 19));
            $address = $requestData->body->address;

            if ($orderTime >= $deliveryTime) setHTTPStatus("400", "The order time is later than the delivery time");

            $resultBasket = query(
                "SELECT price, amount FROM dish_basket
                INNER JOIN dish on dish_basket.idDish = dish.idDish
                WHERE idUser = '$currentUser' AND idOrder IS NULL",
                false
            );
    
            while ($row = mysqli_fetch_assoc($resultBasket)) {
                $orderPrice += $row["price"]*$row["amount"];
            };

            if ($orderPrice == 0) setHTTPStatus("400", "Basket is empty");

            query(
                "INSERT `order`(idOrder, idUser, deliveryTime, orderTime, status, price, address) 
                VALUES ('$idOder', '$currentUser', '$deliveryTime', '$orderTime', 'InProcess', '$orderPrice', '$address')"
            );

            query("UPDATE dish_basket SET idOrder = '$idOder' WHERE idOrder IS NULL");

            setHTTPStatus("200");
        }
    }

?>
