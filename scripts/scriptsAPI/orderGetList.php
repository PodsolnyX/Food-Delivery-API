<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function getListOrder() {

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];

            $resultUser = $link->query(
                "SELECT user.idUser FROM user 
                WHERE email = '$email'")->fetch_assoc();

            $currentUser = $resultUser["idUser"];

            $resultOrders = $link->query("SELECT * FROM `order` WHERE idUser = '$currentUser'");
        
            $orderList = [];
        
            while ($row = mysqli_fetch_assoc($resultOrders)) {
                $orderList[] = [
                    "id" => $row["idOrder"],
                    "orderTime" => str_replace(" ", "T", $row["orderTime"]) . ".000Z",
                    "deliveryTime" => str_replace(" ", "T", $row["deliveryTime"]) . ".000Z",
                    "status" => $row["status"],
                    "price" => $row["price"]
                ];
            }

            echo json_encode($orderList);
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
