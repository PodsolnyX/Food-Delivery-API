<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/JWT.php';
    include_once 'scripts/helpers/database.php';

    function setRatingDish($idDish, $ratingScore) {

        if (!is_numeric($ratingScore) || ($ratingScore < 1 && $ratingScore != 0) || $ratingScore > 10) 
            setHTTPStatus("400", "Incorrect rating score");

        checkDishExists($idDish);

        $token = getTokenFromHeader();
        
        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $result = query(
                "SELECT id FROM dish_basket 
                WHERE idUser = '$idUser' AND idDish = '$idDish' AND idOrder IS NOT NULL"
            );

            if ($result != null) {

                $rating = query(
                    "SELECT rating FROM rating WHERE idUser = '$idUser' AND idDish = '$idDish'"
                )["rating"];

                if ($rating == null && $ratingScore != null && $ratingScore != 0) 
                    query(
                        "INSERT INTO rating(idUser, idDish, rating) 
                        VALUES ('$idUser', '$idDish', '$ratingScore')", 
                        false
                    );
                else if ($ratingScore != null && $ratingScore != 0)
                    query("UPDATE rating SET rating = '$ratingScore' 
                    WHERE idUser = '$idUser' AND idDish = '$idDish'", false);
                else if ($ratingScore != null && $ratingScore == 0)
                    query("DELETE FROM rating WHERE idUser = '$idUser' AND idDish = '$idDish'", false);

                $totalRating = query(
                    "SELECT AVG(rating) AS totalRating FROM rating 
                    WHERE idDish = '$idDish' GROUP BY idDish"
                    )['totalRating'];

                if ($totalRating != 0)
                    query("UPDATE dish SET rating = $totalRating WHERE idDish = '$idDish'", false);
                else 
                    query("UPDATE dish SET rating = NULL WHERE idDish = '$idDish'", false);

                setHTTPStatus("200");
            }
            else setHTTPStatus("404", "The user did not buy this dish");
        }
    }

?>