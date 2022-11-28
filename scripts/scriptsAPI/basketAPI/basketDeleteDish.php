<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function deleteDishFromBasket($idDish) {

        $dish = query("SELECT * FROM dish WHERE idDish = '$idDish'");
        if ($dish == null) setHTTPStatus("404", "Dish not found");
        
        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query("SELECT user.idUser FROM user WHERE email = '$email'");

            $currentUser = $resultUser["idUser"];

            $resultBasketDish = query(
            "SELECT id, amount FROM dish_basket
            WHERE idUser = '$currentUser' AND idDish = '$idDish' AND idOrder IS NULL");

            $idBasketDish = $resultBasketDish["id"];

            if ($idBasketDish == null) setHTTPStatus("404", "This dish is not in the basket");
            else {
                $amountBasketDish = $resultBasketDish["amount"] - 1;
                
                if ($amountBasketDish == 0) {
                    query("DELETE FROM dish_basket WHERE id = '$idBasketDish'");
                    setHTTPStatus("200");
                }
                else {
                    query("UPDATE dish_basket SET amount = '$amountBasketDish' WHERE id = '$idBasketDish'");
                    setHTTPStatus("200");
                }
            }
        }
    }

?>
