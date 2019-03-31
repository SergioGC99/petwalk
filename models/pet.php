<?php
require_once('connection.php');
require_once('exceptions/recordnotfoundexception.php');
class Pet
{
//attributes
    private $id;
    private $name;
    private $gender;
    private $breed;
    private $dateOfBirth;
    private $neutered;
    private $height;

    private $image;
    //getters and setters
    public function getId(){return $this->id;}
    public function setId($id){ $this->id = $id;}
    public function getName() {return $this->name; }
    public function setname($name){ $this->name = $name;}

    public function getGender() {return $this->gender; }
    public function setGender($gender){ $this->gender = $gender;}

    public function getBreed(){return $this->breed; }
    public function setBreed($breed){ $this->breed = $breed;}
    public function getDateOfBirth() {return $this->dateOfBirth; }
    public function serDateOfBirth($dateOfBirth){ $this->dateOfBirth = $dateOfBirth;}
    public function getNeutered(){ return $this->neutered;}
    public function setNeutered($neutered){$this->neutered = $neutered;}
    public function getHeight(){return $this->height;}
    public function setHeight($height){ $this->height = $height;}

    public function getWeight(){ return $this->weight;}
    public function setWeight($image){ $this->image = $image;}
    public function getImage(){ return $this->image;}
    public function setImage($image){ $this->image = $image;}

    //constructor
    public function __construct()
    {
			//recieves 0 arguments
        if (func_num_args() == 0) {
				//empty object
            $this->id = 0;
            $this->name = "";
            $this->gender = "";
            $this->breed = "";
            $this->neutered = "";
            $this->dateOfBirth = "";
            $this->height = "";
            $this->weight = "";
            $this->image = "";

        }
        if (func_num_args() == 1) {
            //get connection
            $connection = MySqlConnection::getConnection();
            //query
            $query = 'select id, name, gender,breed,neutered,Birth,height,weight, image from pet where id = ?';
            //prepare statement
            $command = $connection->prepare($query);
            //parameters
            $x = func_get_arg(0);
            $command->bind_param('i', $x);
            //execute
            $command->execute();
            //bind result
            $command->bind_result($id,$name,$gender,$breed,$neutered,$dateOfBirth,$height,$weight,$image);
            //record was found
            if ($command->fetch()) {
                //pass values to the attributes
                $this->id = $id;
                $this->name = $name;
                $this->gender = $gender;
                $this->breed = $breed;
                $this->neutered = $neutered;
                $this->dateOfBirth = $dateOfBirth;
                $this->height = $height;
                $this->weight = $weight;
                $this->image = $image;

            } else
                throw new RecordNotFoundException();
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
        }
        if (func_num_args() == 8) {
			//object with data from the argument
            $this->id = func_get_arg(0); //first argument received
            $this->name = func_get_arg(1); //second argument received
            $this->gender = func_get_arg(2); //second argument received
            $this->breed = func_get_arg(3); //third argument received
            $this->neutered = func_get_arg(4); //fourth argument received
            $this->dateOfBirth = func_get_arg(5); //fifth argument received
            $this->height = func_get_arg(6); //sixth argument received
            $this->weight = func_get_arg(7); //seventh argument received
            $this->image = func_get_arg(8); //heigth argument received

        }
    }

     //methods
		public function toJson(){
			return json_encode(array(
			'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'breed' => $this->breed,
            'neutered' => $this->neutered,
            'dateOfBirth' => $this->dateOfBirth,
            'height' => $this->height,
            'weight' => $this->weight,
            'image' => $this->image

            
			));
        }

        public static function getAll(){
            $list = array();
            $connection = MySqlConnection::getConnection();
            $query ='select id, name, gender,breed,neutered,Birth,height,weight,image from pet ';
            $command =$connection->prepare($query);
            
            $command->execute();
            $command->bind_result($id,$name,$gender,$breed,$neutered,$dateOfBirth,$height,$weight,$image);

            while($command->fetch()){
                array_push($list, new Pet($id,$name,$gender,$breed,$neutered,$dateOfBirth,$height,$weight,$image));
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
    
}
?>