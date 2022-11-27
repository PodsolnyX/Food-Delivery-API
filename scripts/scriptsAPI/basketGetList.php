<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function getBasketList() {

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];

            $resultUser = $link->query(
                "SELECT user.idUser FROM user 
                WHERE email = '$email'")->fetch_assoc();

            $currentUser = $resultUser["idUser"];

            $resultBasket = $link->query(
            "SELECT dish.idDish, name, price, amount, image FROM dish_basket
            INNER JOIN dish on dish_basket.idDish = dish.idDish
            WHERE idUser = '$currentUser' AND idOrder IS NULL");

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
