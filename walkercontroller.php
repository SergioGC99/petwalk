<?php
//API walker
    require_once('models/walker.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if($parameters != ""){
            try{
                $c = new Walker($parameters);
                echo json_encode(array(
                    'status' => 0,
                    'walker' => json_decode($c->toJson())
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
                'walker' => json_decode(Walker::getAllToJson())
            ));
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //parameters ok
        $parametersOk = false;
        //add contact
        if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name']) &&
        isset($_POST['lastname']) && isset($_POST['phonenumber']) && isset($_POST['ocupation']) && isset($_POST['age']) && isset($_POST['rating'])) {
            //parameters ok
            $parametersOk = true;
            //create contact
            $c = new Walker();
            //assign values
            $c->setEmail($_POST['email']);
            $c->setPassword($_POST['password']);
            $c->setName($_POST['name']);
            $c->setLastname($_POST['lastname']);
            $c->setPhonenumber($_POST['phonenumber']);
            $c->setOcupation($_POST['ocupation']);
            $c->setAge($_POST['age']);
            $c->setRating($_POST['rating']);
            //add
            if ($c->add())
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Walker added successfully'
                ));
            else
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Could not add walker'
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
    
/*
    var walker = [
        {
            id: 0,
            email: '',
            password: '',
            name: '',
            lastname: '',
            phonenumber: 0000000000,
            ocupation: '',
            age: '',
            rating: [
                {
                    score: 0,
                    description: ''
                },
                {
                    score: 0,
                    description: ''
                },
                {
                    score: 0,
                    description: ''
                }
            ]
        }
    ]
    */
    
?>