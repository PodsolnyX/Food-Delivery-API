<?php
    include_once 'scripts/headers.php';

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

    function routerDispatch() {
        $url = isset($_GET['q']) ? $_GET['q'] : '';
        $url = rtrim($url, '/');
        $urlList = explode('/', $url);
    
        if ($urlList[0] == 'api') {
    
            $router = $urlList[1];
            $method = getMethod();
            $requestData = getData($method);
        
            if (file_exists(dirname(realpath(dirname(__FILE__))).'/routers/' . $router . '.php')) {
                include_once 'routers/' . $router . '.php';
                route($method, $urlList, $requestData); 
            }
            else {
                setHTTPStatus("404", "Method not found");
            }
        }
        else {
            setHTTPStatus("404", "Method not found");
        }
    }

?>