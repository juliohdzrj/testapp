<?php
/**
 * User: julio.ramos
 * Date: 01/11/2017
 * Time: 10:23 AM
 */
class MedicalDirectory{
    private $table_name="medica";
	private $table_name2="localidades";
	private $table_name3="especialidades";
	public $id_location="";
	public $id_specialty="";
	public $startRange="";
	public $whereQuery="";
	public $idPromotion="";
	public $idEspecialidad="";

    public function __construct($db)
    {
        $this->conn = $db;
    }
	function getDataNameEnterprise(){
		$query="SELECT `No` AS idMedica,`Empresa`, `Nombre`, `Especialidad`, `Subespecialidad`, `Delegacion`, `Estado` FROM ".$this->table_name." WHERE act_sup=1 ORDER BY Nombre ASC;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_data_name_enterprise'.$errorQuery[2],1);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function searchText(){
		//$query="SELECT DISTINCT `No` AS idPromo, Nombre, sucursal, Direccion, imagen FROM ".$this->table_name." WHERE ".$this->whereQuery." ORDER BY Nombre ASC LIMIT 20;"; LIMIT ".$this->startRange.",20;";
		//$query="SELECT DISTINCT `No` AS idPromo, Nombre, sucursal, Direccion, Especialidad, imagen FROM ".$this->table_name." WHERE ".$this->whereQuery." LIMIT ".$this->startRange.",20;";
		$query="SELECT DISTINCT `No` AS idPromo, Nombre, sucursal, Direccion, Especialidad, imagen FROM ".$this->table_name." WHERE ".$this->whereQuery.";";
		//return $query;
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:search_text_'.$errorQuery[2],2);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function getLocation(){
		$query="SELECT `id_localidades`, `nombre_localidad` FROM `".$this->table_name2."` ORDER BY nombre_localidad ASC;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_location'.$errorQuery[2],3);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function getSpecialty(){
		$query="SELECT `id_especialidad`, `nombre_especialidad` FROM `".$this->table_name3."` WHERE estado=1 ORDER BY nombre_especialidad;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_specialty'.$errorQuery[2],4);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function searchLocationSpecialty(){
		$query="SELECT DISTINCT `No` AS idPromo, Nombre, sucursal, Direccion, imagen, Especialidad FROM `".$this->table_name."` WHERE id_especialidad=".$this->id_specialty." AND id_localidades=".$this->id_location." AND act_sup=1 ORDER BY Nombre;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_search_location_specialty'.$errorQuery[2],5);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function getPromotionForIdNumber(){
		$query="SELECT `No` AS idPromo, Nombre, sucursal, Direccion, imgficha, fecha_update, Promocion_human, Horarios_atencion, Telefonos, Mail, imgficha, id_especialidad FROM `".$this->table_name."` WHERE `No`=".$this->idPromotion." AND `act_sup`=1;";
		//$query="SELECT DISTINCT `No` AS idPromo, Nombre, sucursal, Direccion, imagen FROM `".$this->table_name."` WHERE id_especialidad=".$this->id_specialty." AND id_localidades=".$this->id_location." AND act_sup=1 ORDER BY Nombre;";
		//return array("msg"=>$this->idPromotion,"code"=>$query);
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_promotion_for_id_number'.$errorQuery[2],6);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function especialidadXid(){
		$query = "SELECT imagenesp FROM `".$this->table_name3."` WHERE id_especialidad=".$this->idEspecialidad.";";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:especialidad_id_'.$errorQuery[2],7);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

}