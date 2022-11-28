<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function confirmOrderStatus($idOrder) {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query("SELECT user.idUser FROM user WHERE email = '$email'");

            $currentUser = $resultUser["idUser"];

            $resultOrder = query("SELECT idOrder, idUser FROM `order` WHERE idOrder = '$idOrder'");

            if ($resultOrder != null && $resultOrder["idUser"] == $currentUser) {
                query("UPDATE `order` SET status = 'Delivered' WHERE idOrder = '$idOrder'");
                setHTTPStatus("200");
            }
            else if ($resultOrder != null && $resultOrder["idUser"] != $currentUser) setHTTPStatus("403");
            else setHTTPStatus("404", "Order not found");
        }
    }
?>
