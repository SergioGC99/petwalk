<?php
require_once('models/notification.php');
//get
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['id'])/* $parameters!=""*/){
        try {
            $notification = new Notification($_GET['id']);
            echo json_encode(array(
                'status' => 0,
                'notification' => json_decode($notification->toJson())
            ));
        } catch(RecordNotFoundException $ex) {
            echo json_encode(array(
                'status' => 3,
                'message' => $ex->getMessage()
            ));
        }
        
    }
    else {
        echo json_encode(array(
            'status' => 0,
            'notification' => "No records"
        ));
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['idowner']) && isset($_POST['description']) && isset($_POST['idwalker'])) 
    {

        $notification = new Notification($_POST['idowner'], $_POST['description'], 
        $_POST['idwalker']);
        if($notification->add()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'notification added successfully'
            ));
        } else {
            echo json_encode(array(
                'status' => 2,
                'message' => 'notification was not added to the database'
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 1,
            'message' => 'missing parameters'
        ));
    }
}


//method put for motherboard
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $putData);
    if(isset($putData['data'])){
        $jsonData = json_decode($putData['data'], true);

        $valuesKeyOk = false;

        if(isset($jsonData['idowner']) && isset($jsonData['idwalker'])) {
            try {
                $notification = new Notification($jsonData['idowner'],$jsonData['idwalker']);
                $valuesKeyOk = true;
            } catch(RecordNotFoundException $ex) {
                $valuesKeyOk = false;
                echo json_encode(array(
                    'status' => 3,
                    'message' => $ex->getMessage()
                ));
                die;
            }
        }

        
        if($valuesKeyOk && $notification->update()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Notification was update succesfully'
            ));
        } else {
            echo json_encode(array(
                'status' => 0,
                'message' => 'No change was made'
            ));
        }

        if(!$valuesKeyOk) {
            echo json_encode(array(
                'status' => 4,
                'message' => 'Incorrect JSON key/values in received data'
            ));
        }

    } else {
        echo json_encode(array(
            'status' => 1,
            'message' => 'Missing data parameters'
        ));
    }
}
?>