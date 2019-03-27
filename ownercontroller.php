<?php

    require_once('models/owner.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if($parameters != ""){
            try{
                $c = new Owner($parameters);
                echo json_encode(array(
                    'status' => 0,
                    'owner' => json_decode($c->toJson())
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
                'owner' => json_decode(Owner::getAllToJson())
            ));
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //parameters ok
        $parametersOk = false;
        //add contact
        if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name']) &&
        isset($_POST['lastname']) && isset($_POST['phonenumber'])) {
            //parameters ok
            $parametersOk = true;
            //create contact
            $c = new Owner();
            //assign values
            $c->setEmail($_POST['email']);
            $c->setPassword($_POST['password']);
            $c->setName($_POST['name']);
            $c->setLastname($_POST['lastname']);
            $c->setPhonenumber($_POST['phonenumber']);
            //add
            if ($c->add())
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Owner added successfully'
                ));
            else
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Could not add owner'
                ));
        }
        //parameters were not received
        if (!$parametersOk) {
            echo json_encode(array(
                'status' => 1,
                'errorMessage' => 'Missing parameters'
            ));
        }
    }

    
?>