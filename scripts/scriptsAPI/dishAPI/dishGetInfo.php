<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/connectDB.php';

    function getDishInfo($idDish) {

        $link = connectToDataBase();

        $dish = $link->query("SELECT * FROM dish WHERE idDish = '$idDish'")->fetch_assoc();

        if ($dish == null) {
            $response = [
                "status" => '404',
                "message" => 'Dish not found'
            ];
            echo json_encode($response);
            exit;
        }

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
        http_response_code(200);
    }

?>