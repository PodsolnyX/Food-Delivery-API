<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function createOrder($requestData) {
        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);
            $orderTime = new DateTime();

            if (strtotime($requestData->body->deliveryTime) < ($orderTime->getTimestamp() + 3600*8)) 
                setHTTPStatus("400", "The delivery time is too early");

            if (strtotime($requestData->body->deliveryTime) > ($orderTime->getTimestamp() + 3600*8 + 3600*24*7))
                setHTTPStatus("400", "The delivery time is too late");

            $idOder = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $orderPrice = 0;
            $orderTime = str_replace("T", " ", substr(
                gmdate(DATE_ATOM, ($orderTime->getTimestamp() + 3600*7)), 0, 19));
            $deliveryTime = str_replace("T", " ", substr($requestData->body->deliveryTime, 0, 19));
            $address = $requestData->body->address;

            $resultBasket = query(
                "SELECT price, amount FROM dish_basket
                INNER JOIN dish on dish_basket.idDish = dish.idDish
                WHERE idUser = '$idUser' AND idOrder IS NULL",
                false
            );
    
            while ($row = mysqli_fetch_assoc($resultBasket)) {
                $orderPrice += $row["price"]*$row["amount"];
            };

            if ($orderPrice == 0) setHTTPStatus("400", "Basket is empty");

            query(
                "INSERT `order`(idOrder, idUser, deliveryTime, orderTime, status, price, address) 
                VALUES ('$idOder', '$idUser', '$deliveryTime', '$orderTime', 'InProcess', '$orderPrice', '$address')", 
                false
            );

            query("UPDATE dish_basket SET idOrder = '$idOder' WHERE idOrder IS NULL", false);

            setHTTPStatus("200");
        }
    }

?>
