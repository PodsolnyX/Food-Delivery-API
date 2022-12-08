<?php

    include_once 'scripts/helpers/headers.php';
    include_once 'scripts/helpers/database.php';

    function generateToken($email) {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload = ['email' => $email];
        $secret = base64_encode(bin2hex(random_bytes(32)));

        $nowTime = new DateTime();
        $payload['nbf'] = $nowTime->getTimestamp();
        $payload['exp'] = $nowTime->getTimestamp() + 14400;
        $payload['iat'] = $nowTime->getTimestamp();
        $payload['iss'] = "http://localhost/";
        $payload['aud'] = "http://localhost/";

        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        $verifySignature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($verifySignature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    function getPayload($token) {
        $tokenList = explode('.', $token);
        return json_decode(base64_decode($tokenList[1]), true);
    }

    function isExpired($token) {
        $payload = getPayload($token);
        $nowTime = new DateTime();
        return $payload['exp'] < $nowTime->getTimestamp();
    }

    function isValid($token) {
        $tokenList = explode('.', $token);
        return $tokenList[0] == 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
    }

    function getTokenFromHeader() {
        $authorization = explode(" ", getallheaders()['Authorization']);

        if ($authorization[0] != "Bearer") setHTTPStatus("400", "Incorrect token type");

        if ($authorization[1] == null) setHTTPStatus("400", "Token is null");

        return $authorization[1];
    }

    function isTokenValid($token) {

        $result = query("SELECT token FROM expired_token WHERE token = '$token'");

        if (!isExpired($token) && isValid($token) && $result == null) {
            return true;
        }
        else if (!isValid($token)) setHTTPStatus("403");
        else setHTTPStatus("401");

        return false;
    }
?>