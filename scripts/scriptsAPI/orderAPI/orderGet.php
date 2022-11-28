<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function getOrder($idOrder) {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query("SELECT user.idUser FROM user WHERE email = '$email'");

            $currentUser = $resultUser["idUser"];

            $resultOrder = query("SELECT * FROM `order` WHERE idOrder = '$idOrder' AND idUser = '$currentUser'");

            if ($resultOrder != null) {

                $resultBasket = query(
                    "SELECT dish.idDish, name, price, amount, image FROM dish_basket
                    INNER JOIN dish on dish_basket.idDish = dish.idDish
                    WHERE idUser = '$currentUser' AND idOrder = '$idOrder'",
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
