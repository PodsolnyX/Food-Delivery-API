<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function editProfileUser($requestData) {

        checkValidEditUserData($requestData);

        $token = getTokenFromHeader();

        if (isTokenValid($token)) {

            $idUser = findUserIDByToken($token);

            $fullName = $requestData->body->fullName;
            $address = $requestData->body->address;
            $birthDate = substr($requestData->body->birthDate, 0, 10);
            $gender = $requestData->body->gender;
            $phoneNumber = $requestData->body->phoneNumber;

            query(
                "UPDATE user 
                SET fullName = '$fullName', address = '$address', birthDate = '$birthDate', gender = '$gender', phoneNumber = '$phoneNumber' 
                WHERE idUser = '$idUser'", 
                false
            );

            setHTTPStatus("200");
        }
    }

    function checkValidEditUserData($requestData) {

        $nowTime = new DateTime();

        if (strlen($requestData->body->fullName) < 1) {
            setHTTPStatus("400", "Empty fullname");
        }
        else if (strlen($requestData->body->address) < 1) {
            setHTTPStatus("400", "Empty address");
        }
        else if (strtotime($requestData->body->birthDate) > $nowTime->getTimestamp() || 
        ($requestData->body->birthDate) < '1900-01-01T00:00:00.000Z') {
            setHTTPStatus("400", "Incorrect birthdate");
        }
        else if ($requestData->body->gender != 'Male' && $requestData->body->gender != 'Female') {
            setHTTPStatus("400", "Incorrect gender");
        }
        else if (!preg_match("/^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/", $requestData->body->phoneNumber)) {
            setHTTPStatus("400", "Incorrect phone number");
        }
    }

?>