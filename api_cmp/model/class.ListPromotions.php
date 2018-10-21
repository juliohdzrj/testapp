<?php
/**
 * User: julio.ramos
 * Date: 01/11/2017
 * Time: 10:23 AM
 */
class ListPromotions{
    private $table_name="categoria";
	private $table_name1="cp_pdf_ficha";
    public $tipoRed="";
    public $idCategoria="";
    public $idSubCategoria="";
    public $startRange="";
    public $idPromotion="";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getDataCategory(){
        $query="SELECT nombre_categoria FROM ".$this->table_name." WHERE id_tipo_promo='".$this->tipoRed."' AND id_categoria='".$this->idCategoria."' AND estado=1 ORDER BY id_categoria ASC;";
        try {
		    $stmt=$this->conn->prepare($query);
		   if($stmt->execute()){
			    return array("msg"=>"valdata","code"=>$stmt);
		    }else{
			    $errorQuery=$stmt->errorInfo();
			    throw new Exception('Error:get_data_category_'.$errorQuery[2],1);
		    }
	    }catch (Exception $e){
		    return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
	    }
    }

    function getListPromotions(){
	    $query="SELECT cp_img, socio, fecha_update, idcp_pdf_ficha, tipo_cpimg FROM `".$this->table_name1."` WHERE id_categoria=".$this->idCategoria." AND act_susp=1 LIMIT ".$this->startRange.",20;";
	    //$query="SELECT * FROM `".$this->table_name1."` WHERE id_categoria=".$this->idCategoria." AND act_susp=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_list_promotions_'.$errorQuery[2],2);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function getListSubPromotions(){
		$query="SELECT cp_img, socio, fecha_update, idcp_pdf_ficha, tipo_cpimg FROM `".$this->table_name1."` WHERE id_categoria=".$this->idCategoria." AND id_subcategoria=".$this->idSubCategoria." AND act_susp=1 LIMIT ".$this->startRange.",20;";
		//$query="SELECT * FROM `".$this->table_name1."` WHERE id_categoria=".$this->idCategoria." AND act_susp=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_list_sub_promotions_'.$errorQuery[2],3);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function promotionsForCategory(){
		$query="SELECT COUNT(*) AS promotionsforcategory FROM `".$this->table_name1."` WHERE id_categoria=".$this->idCategoria." AND act_susp=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:promotions_for_category_'.$errorQuery[2],4);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
	function getPromotionForNumber(){
		//return $this->idPromotion;
		$query="SELECT * FROM `".$this->table_name1."` WHERE idcp_pdf_ficha=".$this->idPromotion." AND act_susp=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_promotion_for_number_'.$errorQuery[2],5);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}
}