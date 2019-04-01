<?php
require_once('models/walkdetail.php');
//get
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['idpet']) && isset($_GET['idnotification'])/* $parameters!=""*/){
        try {
            $walkdetail = new WalkDetail($_GET['idpet'],$_GET['idnotification']);
            echo json_encode(array(
                'status' => 0,
                'walkdetail' => json_decode($walkdetail->toJson())
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
            'walkdetail' => "No records"
        ));
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['idpet']) && isset($_POST['idnotification']) && isset($_POST['status']) 
    && isset($_POST['payment'])  && isset($_POST['time'])) {

        $walkdetail = new WalkDetail($_POST['idpet'], $_POST['idnotification'], 
        $_POST['status'], $_POST['payment'],  $_POST['time']);
        if($walkdetail->add()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'WalkDetail added successfully'
            ));
        } else {
            echo json_encode(array(
                'status' => 2,
                'message' => 'WalkDetail was not added to the database'
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

        if(isset($jsonData['idpet']) && isset($jsonData['idnotification'])) {
            try {
                $walkdetail = new WalkDetail($jsonData['idpet'],$jsonData['idnotification']);
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

        if($valuesKeyOk && isset($jsonData['status']))
            $walkdetail->setStatus($jsonData['status']);
        if($valuesKeyOk && isset($jsonData['distance']))
            $walkdetail->setDistance($jsonData['distance']);
        
        if($valuesKeyOk && $walkdetail->update()) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'WalkDetail was update succesfully'
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