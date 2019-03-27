<?php
require_once('models/pet.php');
require_once('exceptions/recordnotfoundexception.php');



if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($parameters != ""){
        try{
            $c = new Pet($parameters);
            echo json_encode(array(
                'status' => 0,
                'pet' => json_decode($c->toJson())
            ));
        }
        catch(RecordNotFoundException $ex) {
            echo json_encode(array(
                'status' => 2,
                'errorMessage' => 'Invalid  id', 
                'details' => $ex->getMessage()
            ));
        }
    }
    else {
        echo json_encode(array(
            'status' => 0,
            'pet' => json_decode(Pet::getAllToJson())
        ));
    }
}
?>
