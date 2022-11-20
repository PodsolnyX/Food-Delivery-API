<?php

    function responseNotFound() {
        $message = [
            "status" => '404',
            "message" => 'Method not found'
        ];
        echo json_encode($message);
    }
?>