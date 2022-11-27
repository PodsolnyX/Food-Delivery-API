<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function editProfileUser($requestData) {

        if(!checkValidEditUserData($requestData)) {
            responseIncorrectData();
            exit;
        };

        $link = connectToDataBase();

        $token = substr(getallheaders()['Authorization'], 7);

        $result = $link->query("SELECT token FROM expired_token WHERE token = '$token'")->fetch_assoc();
        
        if (!isExpired($token) && isValid($token) && $result == null) {

            $email = getPayload($token)["email"];
            $fullName = $requestData->body->fullName;
            $address = $requestData->body->address;
            $birthDate = substr($requestData->body->birthDate, 0, 10);
            $gender = $requestData->body->gender;
            $phoneNumber = $requestData->body->phoneNumber;

            $link->query("UPDATE user SET fullName = '$fullName', address = '$address', birthDate = '$birthDate', gender = '$gender', phoneNumber = '$phoneNumber' WHERE email = '$email'");
            
            http_response_code(200);
        }
        else {
            responseUnauthorized();
            exit;
        }
    }

    function checkValidEditUserData($requestData) {

        $nowTime = new DateTime();

        if (strlen($requestData->body->fullName) < 1) {
            return false;
        }
        else if (strlen($requestData->body->address) < 1) {
            return false;
        }
        else if (strtotime($requestData->body->birthDate) > $nowTime->getTimestamp() || 
        ($requestData->body->birthDate) < '1900-01-01T00:00:00.000Z') {
            return false;
        }
        else if ($requestData->body->gender != 'Male' && $requestData->body->gender != 'Female') {
            return false;
        }
        else if (!preg_match("/^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/", $requestData->body->phoneNumber)) {
            return false;
        }

        return true;
    }

?>