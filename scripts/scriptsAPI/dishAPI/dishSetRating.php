<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/database.php';

    function setRatingDish($idDish, $ratingScore) {

        if ($ratingScore < 0 || $ratingScore > 10) setHTTPStatus("400", "Incorrect rating score");

        checkDishExists($idDish);

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);
            $result = query("SELECT id FROM dish_basket WHERE idUser = '$idUser' AND idDish = '$idDish' AND idOrder IS NOT NULL");

            if ($result != null) {

                $rating = query("SELECT rating FROM rating WHERE idUser = '$idUser' AND idDish = '$idDish'")["rating"];

                if ($rating == null) 
                    query(
                        "INSERT INTO rating(idUser, idDish, rating) 
                        VALUES ('$idUser', '$idDish', '$ratingScore')", 
                        false
                    );
                
                else 
                    query("UPDATE rating SET rating = '$ratingScore' WHERE idUser = '$idUser' AND idDish = '$idDish'", false);

                $totalRating = query(
                    "SELECT AVG(rating) AS totalRating FROM rating 
                    WHERE idDish = '$idDish' GROUP BY idDish"
                    )['totalRating'];

                query("UPDATE dish SET rating = $totalRating WHERE idDish = '$idDish'", false);
                
                setHTTPStatus("200");
            }
            else setHTTPStatus("404", "The user did not buy this dish");
        }
    }

?>