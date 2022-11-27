<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function confirmOrderStatus($idOrder) {

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];

            $resultUser = $link->query(
                "SELECT user.idUser FROM user 
                WHERE email = '$email'")->fetch_assoc();

            $currentUser = $resultUser["idUser"];

            $resultOrder = $link->query("SELECT idOrder FROM `order` WHERE idOrder = '$idOrder' AND idUser = '$currentUser'")->fetch_assoc();

            if ($resultOrder != null) {
                $result = $link->query("UPDATE `order` SET status = 'Delivered' WHERE idOrder = '$idOrder'");
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
