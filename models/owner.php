<?php
require_once('connection.php');
require_once('pet.php');

require_once('exceptions/recordnotfoundexception.php');
Class Owner{
	    
    //attributes
    private $id;
    private $email;
    private $password;
    private $name;
    private $lastname;
    private $phonenumber;
    private $state;
    private $city;
    private $postalcode;
    private $image;

    //getter and setters
    public function getId(){return $this->id;}
    public function setId($id){$this->id=$id;}
    public function getEmail(){return $this->email;}
    public function setEmail($email){$this->email=$email;}
    public function getPassword(){return $this->password;}
    public function setPassword($password){$this->password=$password;}
    public function getName(){return $this->name;}
    public function setName($name){$this->name=$name;}
    public function getLastname(){return $this->lastname;}
    public function setLastname($lastname){$this->lastname=$lastname;}
    public function getPhonenumber(){return $this->phonenumber;}
    public function setPhonenumber($phonenumber){$this->phonenumber=$phonenumber;}
    public function getState(){return $this->state;}
    public function setState($state){$this->state=$state;}
    public function getCity(){return $this->city;}
    public function stetCity($city){$this->city=$city;}
    public function getPostalCode(){return $this->postalcode;}
    public function setPostalCode($postalcode){$this->postalcode=$postalcode;}
    

    public function getImage(){return $this->image;}
    public function setImage($image){$this->image=$image;}
    //constructors
    public function __construct() {
		
		if(func_num_args()){
			
            $this->id=0;
            $this->email='';
            $this->password='';
			$this->name='';
            $this->lastname='';
            $this->phonenumber='';
            $this->state='';
            $this->city='';
            $this->postalcode=0;
            $this->image='';

		}
		
		if(func_num_args()==1) {
				$connection = MySqlConnection::getConnection();
				$query = 'select id, email, password, name,lastname,phonenumber,state, city,postalcode, image from owner where id = ?';
                $command = $connection->prepare($query);
                $x = func_get_arg(0);
				$command->bind_param('i', $x);
				$command->execute();
				$command->bind_result($id,$email,$password,$name,$lastname,$phonenumber,$state,$city,$postalcode,$image);
				if ($command->fetch()) {
					$this->id = $id;
					$this->email=$email;
                    $this->password=$password;
                    $this->name = $name;
                    $this->lastname=$lastname;
                    $this->phonenumber=$phonenumber;
                    $this->state = $state;
                    $this->city=$city;
                    $this->postalcode=$postalcode;
                    $this->image=$image;

				}
				else
					throw new RecordNotFoundException(func_get_arg(0));
				//Close command
				mysqli_stmt_close($command);
				//Close connection
				$connection->close();
			}
		
		if(func_num_args()==10) {
			
            $this->id=func_get_arg(0);
            $this->email=func_get_arg(1);
            $this->password=func_get_arg(2);
			$this->name=func_get_arg(3);
            $this->lastname=func_get_arg(4);
            $this->phonenumber=func_get_arg(5);
            $this->state=func_get_arg(6);
            $this->city=func_get_arg(7);
            $this->postalcode=func_get_arg(8);
            $this->image=func_get_arg(9);

		}
	}

    public function toJson() {
        $phpSelf = $_SERVER['PHP_SELF'];
        $urlParts = explode('/', $phpSelf);
        $lengthLastPart = strlen($urlParts[sizeof($urlParts)-1]);
        $photosPath = substr($phpSelf,0,strlen($phpSelf)- $lengthLastPart);
        return json_encode ( array (
        'id'=>$this->id,
        'email'=>$this->email,
        'password'=>$this->password,
        'name'=>$this->name,
        'lastname'=>$this->lastname,
        'phonenumber'=>$this->phonenumber,
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
       
        return json_encode(array(
            'id'=>$this->id,
        'email'=>$this->email,
        'password'=>$this->password,
        'name'=>$this->name,
        'lastname'=>$this->lastname,
        'phonenumber'=>$this->phonenumber,
        'state'=>$this->state,
        'city'=>$this->city,
        'postalcode'=>$this->postalcode,
        'image' => 'http://'.$_SERVER['HTTP_HOST'].$photosPath.'images/'.$this->image,
            'pets' =>$pet
        ));
    }

    public function getAll() {
		$list =  array();
		$connection = MySqlConnection::getConnection();
		$query = 'select id, email, password, name,lastname,phonenumber,state, city,postalcode, image from owner';
		$command = $connection->prepare($query);
		$command->execute();
		$command->bind_result($id,$email,$password,$name,$lastname,$phonenumber,$state,$city,$postalcode,$image);
		while($command->fetch()){
			array_push($list , new Owner ($id,$email,$password,$name,$lastname,$phonenumber,$state,$city,$postalcode,$image));
		}
		return $list;
	}

    public static function getAllToJson(){
        $jsonArray = array(); //array
        foreach(self::getAll() as $item){
            array_push($jsonArray, json_decode($item->toJson()));
        }
        return json_encode($jsonArray); //return array

     }
        
        public function getPets(){
            $list = array();
             //get connection
             $connection = MySqlConnection::getConnection();
             //query
             $query='select id,name,gender,breed,neutered,Birth,height,weight,image from pet where idowner = ? ';
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