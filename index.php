<?php

    include_once 'scripts/router.php';
    include_once 'scripts/database.php';

    header('Content-type: application/json');

    global $link;
    $link = connectToDataBase();
    
    routerDispatch();

?>