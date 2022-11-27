<?php

include_once 'scripts/responses.php';
include_once 'scripts/scriptsAPI/userRegister.php';
include_once 'scripts/scriptsAPI/userLogin.php';
include_once 'scripts/scriptsAPI/userLogout.php';
include_once 'scripts/scriptsAPI/userGetProfile.php';
include_once 'scripts/scriptsAPI/userEditProfile.php';

    function route($method, $urlList, $requestData) {
        if ($urlList[3] == null) {
            switch ($method) {
                case 'POST':
                    switch ($urlList[2]) {
                        case 'register':
                            registerUser($requestData);
                            break;
    
                        case 'login':
                            loginUser($requestData);
                            break;
    
                        case 'logout':
                            logoutUser();
                            break;
                        
                        default:
                            responseNotFound();
                            break;
                    }
                    break;
                
                case 'GET':
                    if ($urlList[2] == 'profile') {
                        getProfileUser();
                    }
                    else {
                        responseNotFound();
                    }
                    break;
    
                case 'PUT':
                    if ($urlList[2] == 'profile') {
                        editProfileUser($requestData);
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