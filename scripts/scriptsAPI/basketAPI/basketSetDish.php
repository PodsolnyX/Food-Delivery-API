<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function addDishToBasket($idDish) {

        checkDishExists($idDish);

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $resultBasketDish = query(
            "SELECT id, amount FROM dish_basket
            WHERE idUser = '$idUser' AND idDish = '$idDish' AND idOrder IS NULL"
            );

            $idBasketDish = $resultBasketDish["id"];

            if ($idBasketDish == null) {
                query("INSERT INTO dish_basket(idUser, idDish, amount) VALUES ('$idUser', '$idDish', '1')", false);
                setHTTPStatus("200");                  
            }
            else {
                $amountBasketDish = $resultBasketDish["amount"] + 1;
                query("UPDATE dish_basket SET amount = '$amountBasketDish' WHERE id = '$idBasketDish'", false);
                setHTTPStatus("200");
            }
        }
    }

?>
