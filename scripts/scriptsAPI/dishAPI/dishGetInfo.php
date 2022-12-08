<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/database.php';

    function getDishInfo($idDish) {

        checkDishExists($idDish);

        $dish = query("SELECT * FROM dish WHERE idDish = '$idDish'");

        $dishData = [
            "id" => $dish["idDish"],
            "name" => $dish["name"],
            "description" => $dish["description"],
            "price" => floatval($dish["price"]),
            "image" => $dish["image"],
            "vegetarian" => $dish["vegetarian"] == 0 ? false : true,
            "rating" => $dish["rating"] == null ? null : floatval($dish["rating"]),
            "category" => $dish["category"]
        ];

        echo json_encode($dishData);
        setHTTPStatus("200");
    }

?>