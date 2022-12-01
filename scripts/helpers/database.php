<?php

    include_once 'scripts/helpers/headers.php';

    function connectToDataBase() {
        $link = mysqli_connect("127.0.0.1", "backend", "password", "deliveryFood");

        if (!$link) {
            setHTTPStatus("500", "DB Connection error: " . mysqli_connect_error());
            exit;
        }
        else {
            return $link; 
        }
    }

    function query($query, $fetchFlag = true) {
        global $link;

        $result = $link->query($query);

        if (!$result) {
            setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
            exit;
        }

        if ($fetchFlag == true) {
            $result = $result->fetch_assoc();
        }

        return $result;
    }

    function findUserIDByToken($token) {

        $email = getPayload($token)["email"];

        $result = query("SELECT idUser FROM user WHERE email = '$email'");

        if (is_null($result)) setHTTPStatus("403", "User not found");
        
        $idUser = $result["idUser"];

        return $idUser;
    }

    function checkDishExists($idDish) {
        $dish = query("SELECT idDish FROM dish WHERE idDish = '$idDish'");
        if ($dish == null) setHTTPStatus("404", "Dish not found");
        return true;
    }

?>