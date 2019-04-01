<?php
require_once("walker.php");
require_once("owner.php");
require_once('connection.php');
require_once('exceptions/recordnotfoundexception.php');
class Notification{
    //atributes
    private $id;
    private $idowner;
    private $description;
    private $idwalker;
    private $status;

    //getters and setters

    public function getId(){return $this->id;}
    public function setId($id){$this->id=$id;}

    public function getIdOwner(){return $this->idowner;}
    public function setIdOwner($idowner){$this->idowner;}
    
    public function getDescription(){return $this->description;}
    public function setDescription($description){$this->desccription;}

    public function getIdWalker(){return $this->idwalker;}
    public function setIdWalker($idwalker){$this->idwalker=$idwalker;}

    public function getStatus(){return $this->status;}
    public function setStatus($status){$this->status=$status;}

    //constructor

    public function __construct(){
        if(func_num_args()==0){
            $this->id=0;
            $this->idowner=0;
            $this->description="";
            $this->idwalker=0;
            $this->status=0;
        }
        if(func_num_args()==1){
            $connection=MySqlConnection::getConnection();
            //query 
            $query='SELECT `id`, `idowner`, `description`, `idwalker`, `status` FROM `notification` where id=?;';
            //prepare statement 
            $command=$connection->prepare($query);
            $id = func_get_arg(0);
           
            //parameters
            $command->bind_param('i',$id);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id, $idowner, $description, $idwalker, $status);
            //record was found
            
            if($command->fetch())
			{
                $this->id= $id;
                $this->idowner=is_null($idowner) ? null:new Owner($idowner);
                $this->description=$description;
                $this->idwalker= is_null($idwalker) ? null: new Walker($idwalker);
                $this->status= $status;
			}
			else {
                throw new RecordNotFoundException(func_get_arg(0));
            }
            mysqli_stmt_close($command);
            $connection->close();

        }
        if(func_num_args()==2){
            $this->idowner=func_get_arg(0);
            $this->idwalker=func_num_args(1);
        }
        if(func_num_args()==3){
            $this->idowner=func_get_arg(0);
            $this->description=func_get_arg(2);
            $this->idwalker=func_get_arg(1);
        }
        if(func_num_args()==5){
            $this->id=func_get_arg(0);
            $this->idowner=func_get_arg(1);
            $this->description=func_get_arg(2);
            $this->idwalker=func_get_arg(3);
            $this->status=func_get_arg(4);
        }

    }
    public function add() {

        $connection = MysqlConnection::getConnection();
        $statement = "INSERT INTO `notification`(`id`, `idowner`, `description`, `idwalker`, `status`) 
        VALUES (null,?,?,1)";
        $command = $connection->prepare($statement);
        $command->bind_param('isi', $idowner, $description, $idwalker);
            $idowner=$this->idowner;
            $description=$this->description;
            $idwalker = $this->idwalker;            
        $command->execute();
        $affected = $command->affected_rows;
        mysqli_stmt_close($command);
        $connection->close();

        return ($affected > 0);
    }

    public function update()
	{
        $connection = MysqlConnection::getConnection();
        $statement = "UPDATE `notification` SET `status`=0 where idowner=? and idwalker=? and `status`=1;";
        $command = $connection->prepare($statement);

        $command->bind_param('ii',$idowner,$idwalker);
			$idpet=$this->idowner;
			$idwalker=$this->idwalker;
            $command->execute();
            $affected = $command->affected_rows;

        $command->execute();
        $affected += $command->affected_rows;
        mysqli_stmt_close($command);
        $connection->close();

		return ($affected > 0);
	}


    public function toJson() {
        return json_encode(array(
            'id' => $this->id,
            'idpet' => is_null($this->idowner) ? null : json_decode($this->idowner->toJson()) ,
            'description'=>$this->description,
            'idwalker'=>is_null($this->idwalker) ? null : json_decode($this->idwalker->toJson()),
            'status' => $this->status
        ));
    }


}


?>