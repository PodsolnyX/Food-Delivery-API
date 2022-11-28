<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/database.php';
    include_once 'scripts/JWT.php';

    function registerUser($requestData) {

        checkValidRegisterData($requestData);

        $email = $requestData->body->email;

        $user = query("SELECT email FROM user WHERE email = '$email'");

        if (is_null($user)) {

            $idUser = uniqid();
            $password = hash("sha1", $requestData->body->password);
            $fullName = $requestData->body->fullName;
            $address = $requestData->body->address;
            $birthDate = substr($requestData->body->birthDate, 0, 10);
            $gender = $requestData->body->gender;
            $phoneNumber = $requestData->body->phoneNumber;

            query(
                "INSERT INTO user(idUser, fullName, birthDate, gender, address, email, phoneNumber, password) 
                VALUES ('$idUser', '$fullName', '$birthDate', '$gender', '$address', '$email', '$phoneNumber', '$password')"
            );

            echo json_encode(["token" => generateToken($email)]);
            setHTTPStatus("200");
        }
        else setHTTPStatus("400", "Account already exists");
    }

    function checkValidRegisterData($requestData) {

        $nowTime = new DateTime();

        if (strlen($requestData->body->password) < 6) {
            setHTTPStatus("400", "Password length is less than 6");
        }
        else if (!filter_var($requestData->body->email, FILTER_VALIDATE_EMAIL)) {
            setHTTPStatus("400", "Incorrect email");
        }
        else if (strlen($requestData->body->fullName) < 1) {
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