<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function setRatingDish($idDish, $ratingScore) {

        if ($ratingScore < 0 || $ratingScore > 10) setHTTPStatus("400", "Incorrect rating score");

        $dish = query("SELECT * FROM dish WHERE idDish = '$idDish'");
        if ($dish == null) setHTTPStatus("404", "Dish not found");

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $email = getPayload($token)["email"];

            $resultUser = query(
            "SELECT user.idUser FROM user 
            INNER JOIN dish_basket on user.idUser = dish_basket.idUser
            WHERE email = '$email' AND idDish = '$idDish' AND idOrder IS NOT NULL");

            $idUser = $resultUser["idUser"];

            if ($idUser != null) {

                $resultRating = query("SELECT rating.rating FROM rating WHERE idUser = '$idUser' AND idDish = '$idDish'");

                $currentRating = $resultRating["rating"];

                if ($currentRating == null) 
                    query("INSERT INTO rating(idUser, idDish, rating) VALUES ('$idUser', '$idDish', '$ratingScore')");
                
                else 
                    query("UPDATE rating SET rating = '$ratingScore' WHERE idUser = '$idUser' AND idDish = '$idDish'");

                $result = query("SELECT AVG(rating) AS totalRating FROM rating WHERE idDish = '$idDish' GROUP BY idDish");
                $totalRating = $result['totalRating'];
                query("UPDATE dish SET rating = $totalRating WHERE idDish = '$idDish'");
                
                setHTTPStatus("200");
            }
            else setHTTPStatus("404", "The user did not buy this dish");
        }
    }

?>