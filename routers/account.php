<?php

    function route($method, $urlList, $requestData) {
        include_once 'scripts/responses.php';
        if ($urlList[3] == null) {
            switch ($method) {
                case 'POST':
                    switch ($urlList[2]) {
                        case 'register':
                            echo 'api/account/register';
                            break;
    
                        case 'login':
                            echo 'api/account/login';
                            break;
    
                        case 'logout':
                            echo 'api/account/logout';
                            break;
                        
                        default:
                            responseNotFound();
                            break;
                    }
                    break;
                
                case 'GET':
                    if ($urlList[2] == 'profile') {
                        echo 'api/account/profile';
                    }
                    else {
                        responseNotFound();
                    }
                    break;
    
                case 'PUT':
                    if ($urlList[2] == 'profile') {
                        echo 'api/account/profile';
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
        else {
            responseNotFound();
        }
    }

?>