<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function getOrder($idOrder) {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $resultOrder = query("SELECT * FROM `order` WHERE idOrder = '$idOrder'");

            if ($resultOrder != null) {

                if ($resultOrder["idOrder"] != $idUser) setHTTPStatus("403");

                $resultBasket = query(
                    "SELECT dish.idDish, name, price, amount, image FROM dish_basket
                    INNER JOIN dish on dish_basket.idDish = dish.idDish
                    WHERE idUser = '$idUser' AND idOrder = '$idOrder'",
                    false
                );
        
                $dishes = [];
        
                while ($row = mysqli_fetch_assoc($resultBasket)) {
                    $dishes[] = [
                        "id" => $row["idDish"],
                        "name" => $row["name"],
                        "price" => $row["price"],
                        "totalPrice" => $row["price"]*$row["amount"],
                        "amount" => $row["amount"],
                        "image" => $row["image"]
                    ];
                }

                $order = [
                    "id" => $idOrder,
                    "orderTime" => str_replace(" ", "T", $resultOrder["orderTime"]) . ".000Z",
                    "deliveryTime" => str_replace(" ", "T", $resultOrder["deliveryTime"]) . ".000Z",
                    "status" => $resultOrder["status"],
                    "price" => $resultOrder["price"],
                    "dishes" => $dishes,
                    "address" => $resultOrder["address"]
                ];

                echo json_encode($order);
                setHTTPStatus("200");
            }
            else setHTTPStatus("404", "Order not found");
        }
    }

?>
