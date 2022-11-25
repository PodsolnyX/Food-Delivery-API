<?php

    include_once 'scripts/scriptsAPI/dishCheckRating.php';

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
                    echo 'api/dish';
                }
                else if ($urlList[3] == null) {
                    echo 'api/dish/{id}';
                }
                else if ($urlList[3] == 'rating' && $urlList[4] == 'check' && $urlList[5] == null){
                    checkRatingDish($urlList[2]);
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