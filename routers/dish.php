<?php

    include_once 'scripts/scriptsAPI/dishGetList.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[3] == 'rating' && $urlList[4] == null){
                    echo 'api/dish/{id}/rating';
                }
                else {
                    responseNotFound();
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    getDishList($_SERVER['REQUEST_URI']);
                }
                else if ($urlList[3] == null) {
                    echo 'api/dish/{id}';
                }
                else if ($urlList[3] == 'rating' && $urlList[4] == 'check' && $urlList[5] == null){
                    echo 'api/dish/{id}/rating/check';
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