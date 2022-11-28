<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function getListOrder() {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query("SELECT user.idUser FROM user WHERE email = '$email'");

            $currentUser = $resultUser["idUser"];

            $resultOrders = query("SELECT * FROM `order` WHERE idUser = '$currentUser'", false);
        
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
            setHTTPStatus("200");
        }
    }

?>
