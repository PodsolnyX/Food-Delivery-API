<?php

    function route($method, $urlList, $requestData) {
        switch ($method) {
            case 'POST':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    echo 'api/basket/dish/{dishId}';
                }
                else {
                    echo '404';
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    echo 'api/basket';
                }
                else {
                    echo '404';
                }
                break;

            case 'DELETE':
                if ($urlList[2] == 'dish' && $urlList[4] == null) {
                    echo 'api/basket/dish/{dishId}';
                }
                else {
                    echo '404';
                }
                break;

            default:
                echo '404';
                break;
        }
    }

?>