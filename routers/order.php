<?php

    include_once 'scripts/scriptsAPI/orderGet.php';
    include_once 'scripts/scriptsAPI/orderGetList.php';
    include_once 'scripts/scriptsAPI/orderCreate.php';

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        switch ($method) {
            case 'POST':
                if ($urlList[2] == null) {
                    createOrder($requestData);
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
                    getListOrder();
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