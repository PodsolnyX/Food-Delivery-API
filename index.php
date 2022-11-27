<?php

    include_once 'scripts/router.php';
    include_once 'scripts/connectDB.php';

    header('Content-type: application/json');

    global $link;
    $link = connectToDataBase();
    
    routerDispatch();

?>