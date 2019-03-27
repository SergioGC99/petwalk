<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Headers: ");


    //get request URI
    $requestUri = $_SERVER['REQUEST_URI']; 
    //split uri parts
    $uriParts = explode('/', $requestUri);
    //get URI info
    $controller = $uriParts[sizeof($uriParts) - 2]; 
    $parameters = $uriParts[sizeof($uriParts) - 1];
        
        switch ($controller) {
            case strtolower('pet') : require_once('petcontroller.php'); break;
            case strtolower('owner') : require_once('Ownercontroller.php'); break;
            case strtolower('walker') : require_once('Walkercontroller.php'); break;
         
            default:
             echo json_encode(array(
             'status' => 999, 
            'errorMessage' => 'Invalid Controller'
                ));
        }
?>
