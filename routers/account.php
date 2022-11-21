<?php

include_once 'scripts/responses.php';
include_once 'scripts/JWT.php';
include_once 'scripts/connectDB.php';

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

    function loginUser($requestData) {
        $link = connectToDataBase();

        $email = $requestData->body->email;

        $user = $link->query("SELECT email, password FROM user WHERE email = '$email'")->fetch_assoc();

        if (is_null($user)) {
            $response = [
                "status" => '401',
                "message" => 'Incorrect username or password'
            ];
            echo json_encode($response);
        }
        else {
            if (hash("sha1", $requestData->body->password) == $user["password"]) {
                $response = [
                    "token" => generateToken($email)
                ];
                echo json_encode($response);
            }
            else {
                $response = [
                    "status" => '401',
                    "message" => 'Incorrect username or password'
                ];
                echo json_encode($response);
            }
        }
    }

    function registerUser($requestData) {
        $link = connectToDataBase();

        $email = $requestData->body->email;

        $user = $link->query("SELECT email FROM user WHERE email = '$email'")->fetch_assoc();

        if (is_null($user)) {
            $idUser = uniqid();
            $password = hash("sha1", $requestData->body->password);
            $fullName = $requestData->body->fullName;
            $address = $requestData->body->address;
            $birthDate = substr($requestData->body->birthDate, 0, 10);
            $gender = $requestData->body->gender;
            $phoneNumber = $requestData->body->phoneNumber;

            $userInsertResult = $link->query(
                "INSERT INTO user(idUser, fullName, birthDate, gender, address, email, phoneNumber, password) 
                VALUES ('$idUser', '$fullName', '$birthDate', '$gender', '$address', '$email', '$phoneNumber', '$password')");

                if (!$userInsertResult) {
                    echo json_encode($link->error);
                }
                else {
                    $response = [
                        "token" => generateToken($email)
                    ];
                    echo json_encode($response);
                }
        }
        else {
            $response = [
                "status" => '409',
                "message" => 'Account already exists'
            ];
            echo json_encode($response);
        }
    }

?>