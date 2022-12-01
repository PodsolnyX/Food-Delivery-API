<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function deleteDishFromBasket($idDish) {

        checkDishExists($idDish);
        
        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $resultBasketDish = query(
                "SELECT id, amount FROM dish_basket
                WHERE idUser = '$idUser' AND idDish = '$idDish' AND idOrder IS NULL"
            );

            $idBasketDish = $resultBasketDish["id"];

            if ($idBasketDish == null) setHTTPStatus("404", "This dish is not in the basket");
            else {
                $amountBasketDish = $resultBasketDish["amount"] - 1;
                
                if ($amountBasketDish == 0) {
                    query("DELETE FROM dish_basket WHERE id = '$idBasketDish'", false);
                    setHTTPStatus("200");
                }
                else {
                    query("UPDATE dish_basket SET amount = '$amountBasketDish' WHERE id = '$idBasketDish'", false);
                    setHTTPStatus("200");
                }
            }
        }
    }

?>
