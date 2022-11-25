<?php

    include_once 'scripts/scriptsAPI/basketSetDish.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    addDishToBasket($urlList[3]);
                }
                else {
                    responseNotFound();
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    echo 'api/basket';
                }
                else {
                    responseNotFound();
                }
                break;

            case 'DELETE':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    echo 'api/basket/dish/{dishId}';
                }
                else {
                    responseNotFound();
                }
                break;

            default:
                responseNotFound();
                break;
        }
    }

?>