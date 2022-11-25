<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function getOrder($idOrder) {

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $resultOrder = $link->query("SELECT * FROM `order` WHERE idOrder = '$idOrder'")->fetch_assoc();

            if ($resultOrder != null) {

                $email = getPayload($token)["email"];

                $resultUser = $link->query(
                    "SELECT user.idUser FROM user 
                    WHERE email = '$email'")->fetch_assoc();

                $currentUser = $resultUser["idUser"];

                $resultBasket = $link->query(
                    "SELECT dish.idDish, name, price, amount, image FROM dish_basket
                    INNER JOIN dish on dish_basket.idDish = dish.idDish
                    WHERE idUser = '$currentUser' AND idOrder = '$idOrder'");
        
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

            }
            else {
                $response = [
                    "status" => '404',
                    "message" => 'Заказ не найден'
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
