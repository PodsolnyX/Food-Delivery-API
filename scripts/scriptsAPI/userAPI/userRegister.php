<?php

    include_once 'scripts/headers.php';
    include_once 'scripts/JWT.php';

    function registerUser($requestData) {

        if(!checkValidRegisterData($requestData)){
            exit;
        }

        global $link;

        $email = $requestData->body->email;

        $user = $link->query("SELECT email FROM user WHERE email = '$email'");

        if (!$user) {
            setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
            exit;
        }

        $user = $user->fetch_assoc();

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
                setHTTPStatus("500", "DB Error: (" . $link->errno . ") " . $link->error);
            }
            else {
                $response = [
                    "token" => generateToken($email)
                ];
                echo json_encode($response);
                setHTTPStatus("200");
            }
        }
        else {
            setHTTPStatus("400", "Account already exists");
        }
    }

    function checkValidRegisterData($requestData) {

        $nowTime = new DateTime();

        if (strlen($requestData->body->password) < 6) {
            setHTTPStatus("400", "Password length is less than 6");
            return false;
        }
        else if (!filter_var($requestData->body->email, FILTER_VALIDATE_EMAIL)) {
            setHTTPStatus("400", "Incorrect email");
            return false;
        }
        else if (strlen($requestData->body->fullName) < 1) {
            setHTTPStatus("400", "Empty fullname");
            return false;
        }
        else if (strlen($requestData->body->address) < 1) {
            setHTTPStatus("400", "Empty address");
            return false;
        }
        else if (strtotime($requestData->body->birthDate) > $nowTime->getTimestamp() || 
        ($requestData->body->birthDate) < '1900-01-01T00:00:00.000Z') {
            setHTTPStatus("400", "Incorrect birthdate");
            return false;
        }
        else if ($requestData->body->gender != 'Male' && $requestData->body->gender != 'Female') {
            setHTTPStatus("400", "Incorrect gender");
            return false;
        }
        else if (!preg_match("/^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$/", $requestData->body->phoneNumber)) {
            setHTTPStatus("400", "Incorrect phone number");
            return false;
        }

        return true;
    }

?>