<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/scriptsAPI/dishAPI/dishGetInfo.php';
    include_once 'scripts/scriptsAPI/dishAPI/dishSetRating.php';
    include_once 'scripts/scriptsAPI/dishAPI/dishCheckRating.php';
    include_once 'scripts/scriptsAPI/dishAPI/dishGetList.php';

    function route($method, $urlList, $requestData) {
        switch ($method) {
            case 'POST':
                if ($urlList[3] == 'rating' && $urlList[4] == null){
                    setRatingDish($urlList[2], $requestData->body->ratingScore);
                }
                else {
                    setHTTPStatus("404", "Method not found");
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
                    setHTTPStatus("404", "Method not found");
                }
                break;

            default:
                setHTTPStatus("404", "Method not found");
                break;
        }
    }

?>