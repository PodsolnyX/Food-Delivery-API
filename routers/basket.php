<?php

    include_once 'scripts/scriptsAPI/basketGetList.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    echo 'api/basket/dish/{dishId}';
                }
                else {
                    responseNotFound();
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    getBasketList();
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