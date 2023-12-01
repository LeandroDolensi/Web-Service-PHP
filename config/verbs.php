<?php
    global $_DELETE;
    global $_PUT;
    global $_POST;

    if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
        $_DELETE = json_decode(file_get_contents("php://input"), true); 
    }
    if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
        $_PUT = json_decode(file_get_contents("php://input"), true); 
    }

    if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
        $_POST = json_decode(file_get_contents("php://input"), true); 
    }
 ?>