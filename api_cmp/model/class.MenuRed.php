<?php
/**
 * User: julio.ramos
 * Date: 01/11/2017
 * Time: 10:23 AM
 */
class MenuRed{
    private $table_name="categoria";
	private $table_name1="subcategoria";
	private $table_name2="cp_pdf_ficha";
    public $tipoRed="";
    public $idCategoria="";
	public $idSubCategoria="";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getListCategory(){
        $query="SELECT nombre_categoria, id_categoria FROM ".$this->table_name." WHERE id_tipo_promo='".$this->tipoRed."' AND estado=1 ORDER BY id_categoria ASC;";
        try {
		    $stmt=$this->conn->prepare($query);
		   if($stmt->execute()){
			    return array("msg"=>"valdata","code"=>$stmt);
		    }else{
			    $errorQuery=$stmt->errorInfo();
			    throw new Exception('Error:get_list_category_'.$errorQuery[2],1);
		    }
	    }catch (Exception $e){
		    return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
	    }
    }

	function getSubCategory(){
    	$query="SELECT ct.nombre_categoria, ct.id_categoria
				FROM `".$this->table_name."` AS ct
				RIGHT JOIN `".$this->table_name1."` AS sct ON ct.`id_categoria`=sct.`id_categoria`
				WHERE ct.`id_tipo_promo`=".$this->tipoRed." AND ct.`estado`=1 AND sct.`estado`=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_sub_category_'.$errorQuery[2],2);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function numberOfPromotions(){
		$query="SELECT COUNT(*) AS numberPromotions FROM `".$this->table_name2."` WHERE id_categoria=".$this->idCategoria." AND act_susp=1;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:number_of_promotions_'.$errorQuery[2],3);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function getListSubCategory(){
		$query="SELECT nombre_subcategoria, id_subcategoria, id_categoria FROM ".$this->table_name1." WHERE id_categoria=".$this->idCategoria." AND estado=1 ORDER BY nombre_subcategoria ASC;";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:get_list_subcategory_'.$errorQuery[2],4);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}

	function numberOfPromotionsSubCategory(){
		$query="SELECT COUNT(*) AS numberPromotionSubcategory FROM `".$this->table_name2."` WHERE id_categoria=".$this->idCategoria." AND id_subcategoria=".$this->idSubCategoria." AND act_susp=1";
		try {
			$stmt=$this->conn->prepare($query);
			if($stmt->execute()){
				return array("msg"=>"valdata","code"=>$stmt);
			}else{
				$errorQuery=$stmt->errorInfo();
				throw new Exception('Error:number_of_promotion_subcategory'.$errorQuery[2],5);
			}
		}catch (Exception $e){
			return array("msg"=>$e->getMessage(),"code"=>$e->getCode());
		}
	}


}