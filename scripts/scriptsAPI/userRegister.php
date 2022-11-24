<?php
    include_once 'scripts/responses.php';
    include_once 'scripts/JWT.php';
    include_once 'scripts/connectDB.php';

    function registerUser($requestData) {

        if(!checkValidRegisterData($requestData)){
            responseIncorrectData();
            exit;
        }

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
                    http_response_code(200);
                }
        }
        else {
            responseAccountAlreadyExists();
        }
    }

    function checkValidRegisterData($requestData) {

        $nowTime = new DateTime();

        if (strlen($requestData->body->password) < 6) {
            return false;
        }
        else if (!filter_var($requestData->body->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        else if (strlen($requestData->body->fullName) < 1) {
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