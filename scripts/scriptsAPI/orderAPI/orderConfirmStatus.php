<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function confirmOrderStatus($idOrder) {

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $order = query("SELECT idOrder, idUser FROM `order` WHERE idOrder = '$idOrder'");

            if ($order != null && $order["idUser"] == $idUser) {
                query("UPDATE `order` SET status = 'Delivered' WHERE idOrder = '$idOrder'", false);
                setHTTPStatus("200");
            }
            else if ($order != null && $order["idUser"] != $idUser) setHTTPStatus("403");
            else setHTTPStatus("404", "Order not found");
        }
    }
?>
