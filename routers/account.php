<?php

    function route($method, $urlList, $requestData) {
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
                            echo '404';
                            break;
                    }
                    break;
                
                case 'GET':
                    if ($urlList[2] == 'profile') {
                        echo 'api/account/profile';
                    }
                    else {
                        echo '404';
                    }
                    break;
    
                case 'PUT':
                    if ($urlList[2] == 'profile') {
                        echo 'api/account/profile';
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
        else {
            echo '404';
        }
    }

?>