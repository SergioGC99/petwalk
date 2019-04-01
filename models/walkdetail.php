<?php
require_once("notification.php");
require_once("pet.php");
require_once('connection.php');
require_once('exceptions/recordnotfoundexception.php');
class WalkDetail{
    //atributes
    private $id;
    private $idpet;
    private $idnotification;
    private $status;
    private $payment;
    private $distance;
    private $date;
    private $time;
//getters and setters
    public function getId(){return $this->id;}
    public function setId($id){$this->id=$id;}

    public function getIdPet(){return $this->idpet;}
    public function setIdPet($idpet){$this->idpet=$idpet;}

    public function getIdNotification(){return $this->idnotification;}
    public function setIdNotification($idnotification){$this->idnotification=$idnotification;}

    public function getStatus(){return $this->status;}
    public function setStatus($status){$this->status=$status;}

    public function getPayment(){return $this->payment;}
    public function setPayment($payment){$this->payment=$payment;}

    public function getDistance(){return $this->distance;}
    public function setDistance($distance){$this->distance=$distance;}

    public function getDate(){return $this->date;}
    public function setDate($date){$this->date=$date;}

    public function getTime(){return $this->time;}
    public function setTime($time){$this->time=$time;}


    //constructor
    public function __construct(){
        if(func_num_args()==0){
            $this->id=0;
            $this->idpet=new Pet();
            $this->idnotification=new Notification();
            $this->status=0;
            $this->payment=0;
            $this->date="";
            $this->time="";
        }
        if(func_num_args()==2){
			 $connection=MySqlConnection::getConnection();
            //query 
            $query='SELECT `id`, `idpet`, `idnotification`, `status`, `payment`, `distance`, `date`, `time` FROM `walk_detail` WHERE idpet=? and idnotification=? and `status`=1;';
            //prepare statement 
            $command=$connection->prepare($query);
            $idpet = func_get_arg(0);
            $idnotification=func_get_arg(1);
            //parameters
            $command->bind_param('ii',$idpet,$idnotification);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id, $idpet, $idnotification, $status, $payment, $distance, $date, $time);
            //record was found
            
            if($command->fetch())
			{
                $this->id= $id;
                $this->idpet= is_null($idpet) ? null : new Pet($idpet);
                $this->idnotification= is_null($idnotification) ? null: new Notification($idnotification);
                $this->status= $status;
                $this->payment= $payment;
                $this->distance= $distance;
                $this->date= $date;
                $this->time= $time;
			}
			else {
                throw new RecordNotFoundException(func_get_arg(0));
            }
            mysqli_stmt_close($command);
            $connection->close();

        }//finconstructor that get two params
        if(func_num_args()==7){
            $this->id=func_get_arg(0); 
            $this->idpet=func_get_arg(1);
            $this->idnotification=func_get_arg(2);
            $this->status=func_get_arg(3);
            $this->payment=func_get_arg(4);
            $this->date=func_get_arg(5);
            $this->time=func_get_arg(6);
        }//finconstructor that get six params
        if(func_num_args()==8){
            $this->id=func_get_arg(0); 
            $this->idpet=func_get_arg(1);
            $this->idnotification=func_get_arg(2);
            $this->status=func_get_arg(3);
            $this->payment=func_get_arg(4);
            $this->distance=func_get_arg(5);
            $this->date=func_get_arg(6);
            $this->time=func_get_arg(7);
        }
        if(func_num_args()==6){
            $this->idpet=func_get_arg(0);
            $this->idnotification=func_get_arg(1);
            $this->status=func_get_arg(2);
            $this->payment=func_get_arg(3);
            $this->date=func_get_arg(4);
            $this->time=func_get_arg(5);
        }
    }

    public function add() {

        $connection = MysqlConnection::getConnection();
        $statement = "INSERT INTO `walk_detail`(`id`, `idpet`, `idnotification`, `status`, `payment`, `distance`, `date`, `time`) 
        VALUES (null,?,?,?,?,null,NOW(),?)";
        $command = $connection->prepare($statement);
        $command->bind_param('iiiiss', $idpet, $idnotification, $status, $payment, $time);
            $idpet = $this->idpet;
            $idnotification = $this->idnotification;
            $status = $this->status;
            $payment = $this->payment;
            $date = $this->date;
            $time = $this->time;
        $command->execute();
        $affected = $command->affected_rows;
        mysqli_stmt_close($command);
        $connection->close();

        return ($affected > 0);
    }

    public function update()
	{
        $connection = MysqlConnection::getConnection();
        $statement = "UPDATE `walk_detail` SET `status`=?, `distance`=? where idpet=? and idnotification=? and `status`=1;";
        $command = $connection->prepare($statement);

        $command->bind_param('iiii',$status,$distance,$idpet,$idnotification);
            $status=$this->status;
            $distance=$this->distance;
			$idpet=$this->idpet;
			$idnotification=$this->idnotification;
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
            'idpet' => is_null($this->idpet) ? null : json_decode($this->idpet->toJson()) ,
            'idnotification'=>is_null($this->idnotification) ? null : json_decode($this->idnotification->toJson()),
            'status' => $this->status,
            'payment'=>$this->payment,
            'distance'=>$this->distance,
            'date'=>$this->date,
            'time'=>$this->time
        ));
    }

}


?>