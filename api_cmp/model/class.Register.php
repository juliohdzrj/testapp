<?php
/**
 * User: julio.ramos
 * Date: 01/11/2017
 * Time: 10:23 AM
 */
class Register{
    private $table_name="userext";
	private $timestamp;
	public $dataRegister=array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

	function insertDataUser(){
		$this->getTimestamp();
		return $this->insertDataUser();


		$query = "INSERT INTO
                    ".$this->table_name."
                SET
                    nombre_user = ?, csp = ?, diremail = ?, nombres = ?, apellidos = ?, fecha_create = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->nombreUsuario);
		$stmt->bindParam(2, $this->contraseña);
		$stmt->bindParam(3, $this->email2);
		$stmt->bindParam(4, $this->nombres);
		$stmt->bindParam(5, $this->appellidos);
		$stmt->bindParam(6, $this->timestamp);
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:val_us_'.$errorQuery[2],1);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function getTimestamp(){
		date_default_timezone_set('Mexico/General');
		$this->timestamp = date('Y-m-d H:i:s');
	}

}