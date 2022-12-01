<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function getListOrder() {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $resultOrders = query("SELECT * FROM `order` WHERE idUser = '$idUser'", false);
        
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
