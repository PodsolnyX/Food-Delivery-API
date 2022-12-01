<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function getBasketList() {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $resultBasket = query(
            "SELECT dish.idDish, name, price, amount, image FROM dish_basket
            INNER JOIN dish on dish_basket.idDish = dish.idDish
            WHERE idUser = '$idUser' AND idOrder IS NULL", false);

            $basket = [];

            while ($row = mysqli_fetch_assoc($resultBasket)) {
                $basket[] = [
                    "id" => $row["idDish"],
                    "name" => $row["name"],
                    "price" => $row["price"],
                    "totalPrice" => $row["price"]*$row["amount"],
                    "amount" => $row["amount"],
                    "image" => $row["image"]
                ];
            }

            echo json_encode($basket);
            setHTTPStatus("200");
        }
    }

?>
