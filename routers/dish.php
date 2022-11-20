<?php

    function route($method, $urlList, $requestData) {
        switch ($method) {
            case 'POST':
                if ($urlList[3] == 'rating' && $urlList[4] == null){
                    echo 'api/dish/{id}/rating';
                }
                else {
                    echo '404';
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    echo 'api/dish';
                }
                else if ($urlList[3] == null) {
                    echo 'api/dish/{id}';
                }
                else if ($urlList[3] == 'rating' && $urlList[4] == 'check' && $urlList[5] == null){
                    echo 'api/dish/{id}/rating/check';
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