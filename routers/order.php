<?php

    function route($method, $urlList, $requestData) {
        switch ($method) {
            case 'POST':
                if ($urlList[2] == null) {
                    echo 'api/order';
                }
                else if ($urlList[3] == 'status' && $urlList[4] == null){
                    echo 'api/order/{id}/status';
                }
                else {
                    echo '404';
                }
                break;
            
            case 'GET':
                if ($urlList[2] == null) {
                    echo 'api/order';
                }
                else if ($urlList[3] == null) {
                    echo 'api/order/{id}';
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