<?php

    function responseIncorrectData() {
        $response = [
            "status" => '400',
            "message" => 'Incorrect data'
        ];
        echo json_encode($response);
    }

    function responseBadRequest() {
        $response = [
            "status" => '400',
            "message" => 'Bad request'
        ];
        echo json_encode($response);
    }

    function responseIncorrectPasswordOrLogin() {
        $response = [
            "status" => '401',
            "message" => 'Incorrect username or password'
        ];
        echo json_encode($response);
    }

    function responseNotFound() {
        $response = [
            "status" => '404',
            "message" => 'Method not found'
        ];
        echo json_encode($response);
    }

    function responseAccountAlreadyExists() {
        $response = [
            "status" => '409',
            "message" => 'Account already exists'
        ];
        echo json_encode($response);
    }
?>