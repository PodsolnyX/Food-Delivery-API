<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/connectDB.php';

    function getDishList($search) {

        $link = connectToDataBase();

        $explodeSearch = searchParse($search);

        if ($explodeSearch["categories"] == null) {
            $response = [
                "status" => '400',
                "message" => 'Категория не выбрана'
            ];
            echo json_encode($response);
            exit;
        };

        $categories = join("','", $explodeSearch["categories"]);
        $sorting = $explodeSearch["sorting"];
        $vegetarian = $explodeSearch["vegetarian"];
        $pageNumber = $explodeSearch["pageNumber"];

        $pageSize = 8;
        $startId = ($pageSize*$pageNumber - 8);
        $endId = $pageSize*$pageNumber;

        $result = $link->query("SELECT * FROM dish WHERE ((category IN ('$categories')) AND (vegetarian = $vegetarian)) ORDER BY $sorting LIMIT $startId, $endId");
       
        if ($result->fetch_assoc() == null) {
            $response = [
                "status" => '400',
                "message" => 'Invalid value for attribute page'
            ];
            echo json_encode($response);
            exit;
        }
        echo $categories;

        $countDishes = $link->query("SELECT COUNT(*) AS countDishes FROM dish WHERE category IN ('$categories') AND vegetarian = $vegetarian")->fetch_assoc();

        $countDishes = $countDishes["countDishes"];

        $pagination = [
            "size" => $pageSize,
            "count" => $countDishes <= $pageSize ? 1 : ($countDishes % $pageSize == 0 ? intval($countDishes / $pageSize) : intval($countDishes / $pageSize) + 1),
            "current" => $pageNumber 
        ];

        while ($dish = mysqli_fetch_assoc($result)) {
            $dishes[] = [
                "id" => $dish["idDish"],
                "name" => $dish["name"],
                "description" => $dish["description"],
                "price" => $dish["price"],
                "image" => $dish["image"],
                "vegetarian" => $dish["vegetarian"] == 0 ? 0 : 1,
                "rating" => $dish["rating"],
                "category" => $dish["category"]
            ];
        }

        $dishList = [
            "dishes" => $dishes,
            "pagination" => $pagination
        ];

        echo json_encode($dishList);

        http_response_code(200);
    }

    function searchParse($search) {
        
        $pageRegex = "/[\?&]page=(?<pageNumber>[1-9][0-7]*)/m";
        $categoriesRegex = "/[\?&]categories=(?<category>\w*)/m";
        $vegetarianRegex = "/[\?&]vegetarian=(?<vegetarian>\w*)/m";
        $sortingRegex = "/[\?&]sorting=(?<sorting>\w*)/m";

        preg_match_all($categoriesRegex, $search, $matches, PREG_PATTERN_ORDER);
        $categories = $matches["category"];

        preg_match_all($pageRegex, $search, $matches, PREG_PATTERN_ORDER);
        $pageNumber = $matches["pageNumber"];

        preg_match_all($vegetarianRegex, $search, $matches, PREG_PATTERN_ORDER);
        $vegetarian = $matches["vegetarian"];

        if ($vegetarian[0] == "true") {
            $vegetarian[0] = "1";
        }
        else if ($vegetarian[0] == "false") {
            $vegetarian[0] = "0";
        }

        preg_match_all($sortingRegex, $search, $matches, PREG_PATTERN_ORDER);
        $sorting = $matches["sorting"];

        switch ($sorting[0]) {
            case 'NameAsc':
                $sorting[0] = 'name';
                break;
            case 'NameDesc':
                $sorting[0] = 'name DESC';
                break;
            case 'PriceAsc':
                $sorting[0] = 'price';
                break;
            case 'PriceDesc':
                $sorting[0] = 'price DESC';
                break;
            case 'RatingAsc':
                $sorting[0] = 'rating';
                break;
            case 'RatingDesc':
                $sorting[0] = 'rating DESC';
                break;
            case null:
                $sorting[0] = '';
                break;
            default:
                $response = [
                    "status" => '400',
                    "message" => 'Invalid sorting'
                ];
                echo json_encode($response);
                exit;
                break;
        }

        $explodeSearch = [
            "categories" => $categories,
            "sorting" => $sorting[0],
            "vegetarian" => $vegetarian[0] != null ? $vegetarian[0] : "0 OR 1",
            "pageNumber" => $pageNumber[0] != null ? $pageNumber[0] : 1,
        ];
     
        return $explodeSearch;
    }

?>
