<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/scriptsAPI/orderAPI/orderGet.php';
    include_once 'scripts/scriptsAPI/orderAPI/orderGetList.php';
    include_once 'scripts/scriptsAPI/orderAPI/orderCreate.php';
    include_once 'scripts/scriptsAPI/orderAPI/orderConfirmStatus.php';

    function route($method, $urlList, $requestData) {
        switch ($method) {
            case 'POST':
                if ($urlList[2] == null) {
                    createOrder($requestData);
                }
                else if ($urlList[3] == 'status' && $urlList[4] == null){
                    confirmOrderStatus($urlList[2]);
                }
                else {
                    setHTTPStatus("404", "Method not found");
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
                    setHTTPStatus("404", "Method not found");
                }
                break;

            default:
                setHTTPStatus("404", "Method not found");
                break;
        }
    }

?>