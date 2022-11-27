<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/scriptsAPI/userAPI/userRegister.php';
    include_once 'scripts/scriptsAPI/userAPI/userLogin.php';
    include_once 'scripts/scriptsAPI/userAPI/userLogout.php';
    include_once 'scripts/scriptsAPI/userAPI/userGetProfile.php';
    include_once 'scripts/scriptsAPI/userAPI/userEditProfile.php';

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
                            setHTTPStatus("404", "Method not found");
                            break;
                    }
                    break;
                
                case 'GET':
                    if ($urlList[2] == 'profile') {
                        getProfileUser();
                    }
                    else {
                        setHTTPStatus("404", "Method not found");
                    }
                    break;
    
                case 'PUT':
                    if ($urlList[2] == 'profile') {
                        editProfileUser($requestData);
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
        else {
            setHTTPStatus("404", "Method not found");
        }
    }

?>