<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';

    function getDishList($search) {

        $explodeSearch = searchParse($search);
        
        $categories = join("','", $explodeSearch["categories"]);
        $sorting = $explodeSearch["sorting"];
        $vegetarian = $explodeSearch["vegetarian"];
        $pageNumber = $explodeSearch["pageNumber"];

        $pageSize = 8;
        $startId = ($pageSize*$pageNumber - $pageSize);

        $result = query(
            "SELECT * FROM dish WHERE ((category IN ('$categories')) AND (vegetarian = $vegetarian)) 
            ORDER BY $sorting LIMIT $startId, $pageSize", 
            false
        );

        $countDishes = query(
            "SELECT COUNT(*) AS countDishes FROM dish 
            WHERE ((category IN ('$categories')) AND (vegetarian = $vegetarian))"
        )["countDishes"];

        if ($countDishes == 0) setHTTPStatus("400", "Dishes of this type were not found");
        
        $pagination = [
            "size" => $pageSize,
            "count" => $countDishes < $pageSize ? 1 : ($countDishes % $pageSize == 0 ? intval($countDishes / $pageSize) : intval($countDishes / $pageSize) + 1),
            "current" => $pageNumber 
        ];

        while ($dish = mysqli_fetch_assoc($result)) {
            echo "1";
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

        if (!$dishes) setHTTPStatus("400", "Invalid value for attribute page");

        $dishList = [
            "dishes" => $dishes,
            "pagination" => $pagination
        ];

        echo json_encode($dishList);
        // setHTTPStatus("200");
    }

    function searchParse($search) {
        
        $pageRegex = "/[\?&]page=(?<pageNumber>[\-\.\w]*)/m";
        $categoriesRegex = "/[\?&]categories=(?<category>\w*)/m";
        $vegetarianRegex = "/[\?&]vegetarian=(?<vegetarian>\w*)/m";
        $sortingRegex = "/[\?&]sorting=(?<sorting>\w*)/m";

        $constCategories = ['Pizza', 'Soup', 'Wok', 'Drink', 'Dessert'];

        preg_match_all($categoriesRegex, $search, $matches, PREG_PATTERN_ORDER);
        $categories = $matches["category"];

        if (empty($categories)) setHTTPStatus("400", "Category not selected");

        if (!(count(array_intersect($categories, $constCategories)) == count($categories))) setHTTPStatus("400", "Invalid category");

        preg_match_all($pageRegex, $search, $matches, PREG_PATTERN_ORDER);
        $pageNumber = $matches["pageNumber"];

        $num = $pageNumber[0];

        if ($num != null && (($num != intval($num)) || ($num <= 0))) setHTTPStatus("400", "Invalid page number");

        preg_match_all($vegetarianRegex, $search, $matches, PREG_PATTERN_ORDER);
        $vegetarian = $matches["vegetarian"];

        if ($vegetarian[0] == "true") $vegetarian[0] = "1";
        else if ($vegetarian[0] == "false") $vegetarian[0] = "0";
        else if ($vegetarian[0] != null) setHTTPStatus("400", "Invalid vegetarian");

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
                setHTTPStatus("400", "Invalid sorting");
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
