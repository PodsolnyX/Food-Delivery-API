<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/scriptsAPI/basketAPI/basketGetList.php';
    include_once 'scripts/scriptsAPI/basketAPI/basketSetDish.php';
    include_once 'scripts/scriptsAPI/basketAPI/basketDeleteDish.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    addDishToBasket($urlList[3]);
                }
                else {
                    setHTTPStatus("404", "Method not found");
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    getBasketList();
                }
                else {
                    setHTTPStatus("404", "Method not found");
                }
                break;

            case 'DELETE':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    deleteDishFromBasket($urlList[3]);
                }
                else {
                    setHTTPStatus("404", "Method not found");
                }
                break;

            default:
                setHTTPStatus("404", "Method not found");
                break;
        }
    }

?>