<?php
require_once('connection.php');
require_once('pet.php');

require_once('exceptions/recordnotfoundexception.php');
    class Walker{
        //atributes
        private $id;
        private $email;
        private $password;
        private $name;
        private $lastname;
        private $phonenumber;
        private $ocupation;
        private $age;
        private $rating;
        private $state;
        private $city;
        private $postalcode;
        private $image;

        //getters and setters
        public function getId() {return $this->id;}
        public function setId($id) {$this->id = $id;}

        public function getEmail() {return $this->email;}
        public function setEmail($email) {$this->email = $email;}

        public function getPassword() {return $this->password;}
        public function setPassword($password) {$this->password = $password;}

        public function getName() {return $this->name;}
        public function setName($name) {$this->name = $name;}

        public function getLastname() {return $this->lastname;}
        public function setLastname($lastname) {$this->lastname = $lastname;}

        public function getPhonenumber() {return $this->phonenumber;}
        public function setPhonenumber($phonenumber) {$this->phonenumber = $phonenumber;}

        public function getOcupation() {return $this->ocupation;}
        public function setOcupation() {$this->ocupation = $ocupation;}

        public function getAge() {return $this->age;}
        public function setAge($age) {$this->age = $age;}

        public function getRating() {return $this->rating;}
        public function setRating($rating) {$this->rating = $rating;}


        public function getState(){return $this->state;}
        public function setState($state){$this->state=$state;}
        public function getCity(){return $this->city;}
        public function setCity($city){$this->city=$city;}
        public function getPostalCode(){return $this->postalcode;}
        public function setPostalCode($postalcode){$this->postalcode=$postalcode;}

        public function getImage() {return $this->image;}
        public function setImage($image) {$this->image = $image;}
        //constructor
        public function __construct() {
            if(func_num_args()){
                $this->id = '';
                $this->email = '';
                $this->password = '';
                $this->name = '';
                $this->lastname = '';
                $this->phonenumber = '';
                $this->ocupation = '';
                $this->age = '';
                $this->rating = "";

                $this->state = '';
                $this->city = '';
                $this->postalcode = "";
                $this->image = '';


            }
            if(func_num_args() == 1){
                $connection = MySqlConnection::getConnection(); //get connection
                $query = 'select id, email, password, name, lastname, phonenumber, ocupation, age, rating, state,city,postalcode, image 
                from walker where id = ?'; //query
                $command = $connection->prepare($query); //prepare statement
                $x = func_get_arg(0);
                $command->bind_param('i', $x); //parameters
                $command->execute(); //execute
                $command->bind_result($id, $email, $password, $name, $lastname, $phonenumber, $ocupation, $age, $rating,$state,$city,$postalcode,$image); //bind results
                //record was found
                if ($command->fetch()) {
                    //pass values to the attributes
                    $this->id = $id;
                    $this->email = $email;
                    $this->password = $password;
                    $this->name = $name;
                    $this->lastname = $lastname;
                    $this->phonenumber = $phonenumber;
                    $this->ocupation = $ocupation;
                    $this->age = $age;
                    $this->rating = $rating;
                    $this->state = $state;
                    $this->city = $city;
                    $this->postalcode = $postalcode;
                    $this->image = $image;
                }
                else
                    throw new RecordNotFoundException(func_get_arg(0));
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            if(func_num_args() == 13) {
                $this->id = func_get_arg(0);
                $this->email = func_get_arg(1);
                $this->password = func_get_arg(2);
                $this->name = func_get_arg(3);
                $this->lastname = func_get_arg(4);
                $this->phonenumber = func_get_arg(5);
                $this->ocupation = func_get_arg(6);
                $this->age = func_get_arg(7);
                $this->rating = func_get_arg(8);
                $this->state = func_get_arg(9);
                $this->city = func_get_arg(10);
                $this->postalcode = func_get_arg(11);
                $this->image = func_get_arg(12);
            }
        }

        public function toJson() {
            $phpSelf = $_SERVER['PHP_SELF'];
            $urlParts = explode('/', $phpSelf);
            $lengthLastPart = strlen($urlParts[sizeof($urlParts)-1]);
            $photosPath = substr($phpSelf,0,strlen($phpSelf)- $lengthLastPart);
            return json_encode(array(
                'id' => $this->id,
                'email' => $this->email,
                'password'=>$this->password,
                'name' => $this->name,
                'lastname'=>$this->lastname,
                'phonenumber'=>$this->phonenumber,
                'ocupation'=>$this->ocupation,
                'age'=>$this->age,
                'rating'=>$this->rating,
                'state'=>$this->state,
                'city'=>$this->city,
                'postalcode'=>$this->postalcode,
        'image' => 'http://'.$_SERVER['HTTP_HOST'].$photosPath.'images/'.$this->image
            ));
        }



        public function toJsonFull(){
            //pets
            $pet = array();
            foreach($this->getPets() as $item){
                array_push($pet, json_decode($item->toJson()));
            }
            $phpSelf = $_SERVER['PHP_SELF'];
            $urlParts = explode('/', $phpSelf);
            $lengthLastPart = strlen($urlParts[sizeof($urlParts)-1]);
            $photosPath = substr($phpSelf,0,strlen($phpSelf)- $lengthLastPart);
            return json_encode(array(
                'id' => $this->id,
                'email' => $this->email,
                'password'=>$this->password,
                'name' => $this->name,
                'lastname'=>$this->lastname,
                'phonenumber'=>$this->phonenumber,
                'ocupation'=>$this->ocupation,
                'age'=>$this->age,
                'rating'=>$this->rating,
                'state'=>$this->state,
                'city'=>$this->city,
                'postalcode'=>$this->postalcode,
                'image' => 'http://'.$_SERVER['HTTP_HOST'].$photosPath.'images/'.$this->image,
                'pets' =>$pet
            ));
        }
        public static function getAll() {
            $list = array(); //create list
            //get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select id, email, password, name, lastname, phonenumber, ocupation, age, rating, state,city,postalcode,image 
            from walker';
			//prepare statement
			$command = $connection->prepare($query);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $email, $password, $name, $lastname, $phonenumber, $ocupation, $age, $rating,$state,$city,$postalcode,$image);
			//fetch data
			while ($command->fetch()) {
				//add contact to list
				array_push($list, new Walker($id,$email, $password, $name, $lastname, $phonenumber, $ocupation, $age, $rating,$state,$city,$postalcode,$image));
			}
            return $list; //return list
        }

        //returs a JSON array with all the contacts
        public static function getAllToJson() {
            $jsonArray = array(); //create JSON array
            //read items
            foreach(self::getAll() as $item) {
                array_push($jsonArray, json_decode($item->toJson()));
            }
            return json_encode($jsonArray); //return JSON array
        }

        public function getPets(){
            $list = array();
             //get connection
             $connection = MySqlConnection::getConnection();
             //query
             $query='select p.id,p.name,p.gender,p.breed,p.neutered,p.Birth,p.height,p.weight,p.image
             from pet as p join walker_pet on  p.id = walker_pet.idpet
             join walker on walker.id = walker_pet.idwalker where idwalker = ? ';
            //prepare statement
            $command = $connection->prepare($query);
            //bind params
            $command->bind_param('i', $this->id);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id,$name,$gender,$breed,$neutered,$birth,$height,$weight,$image);
			//fetch data
			while ($command->fetch()) {
                //add contact to list
				array_push($list, new Pet($id,$name,$gender,$breed,$neutered,$birth,$height,$weight,$image));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //return list
            return $list; 
        }

    }
?>    