<?php

include_once 'scripts/scriptsAPI/orderGet.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[2] == null) {
                    echo 'api/order';
                }
                else if ($urlList[3] == 'status' && $urlList[4] == null){
                    echo 'api/order/{id}/status';
                }
                else {
                    responseNotFound();
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    echo 'api/order';
                }
                else if ($urlList[3] == null) {
                    getOrder($urlList[2]);
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