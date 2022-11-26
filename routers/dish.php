<?php

    include_once 'scripts/responses.php';
    include_once 'scripts/scriptsAPI/dishGetInfo.php';
    include_once 'scripts/scriptsAPI/dishSetRating.php';
    include_once 'scripts/scriptsAPI/dishCheckRating.php';
    include_once 'scripts/scriptsAPI/dishGetList.php';


    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[3] == 'rating' && $urlList[4] == null){
                    setRatingDish($urlList[2], $requestData->body->ratingScore);
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
                    getDishInfo($urlList[2]);
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