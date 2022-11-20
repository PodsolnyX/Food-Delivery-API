<?php

    function getData($method) {
        $data = new stdClass();

        if ($method != "GET") {
            $data->body = json_decode(file_get_contents('php://input'));
        }
        $data->parameters = [];
            $dataGet = $_GET;
            foreach ($dataGet as $key => $value) {
                if ($key != "q") {
                    $data->parameters[$key] = $value;
                }
            }
        return $data;
    }

    function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    header('Content-type: application/json');
    include_once 'scripts/responses.php';

    $link = mysqli_connect("127.0.0.1", "backend", "password", "deliveryFood");

    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Код ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    $url = isset($_GET['q']) ? $_GET['q'] : '';
    $url = rtrim($url, '/');
    $urlList = explode('/', $url);

    if ($urlList[0] == 'api') {

        $router = $urlList[1];
        $method = getMethod();
        $requestData = getData($method);
    
        if (file_exists(realpath(dirname(__FILE__)).'/routers/' . $router . '.php')) {
            include_once 'routers/' . $router . '.php';
            route($method, $urlList, $requestData); 
        }
        else {
            responseNotFound();
        }
    }
    else {
        responseNotFound();
    }

?>