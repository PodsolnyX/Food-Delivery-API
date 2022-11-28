<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';

    function getDishInfo($idDish) {

        checkDishExists($idDish);

        $dish = query("SELECT * FROM dish WHERE idDish = '$idDish'");

        $dishData = [
            "id" => $dish["idDish"],
            "name" => $dish["name"],
            "description" => $dish["description"],
            "price" => $dish["price"],
            "image" => $dish["image"],
            "vegetarian" => $dish["vegetarian"] == 0 ? 0 : 1,
            "rating" => $dish["rating"],
            "category" => $dish["category"]
        ];

        echo json_encode($dishData);
        setHTTPStatus("200");
    }

?>