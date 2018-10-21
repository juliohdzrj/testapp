<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 24/07/2018
 * Time: 06:26 PM
 */
session_start();
set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();
$headers = apache_request_headers();
if($headers["Authorization"]!="35f13ff714ce50c37baccead2e5a90c5="){
	exit(0);
}

//$dataResult=array("msg"=>"continue","datosapi"=>$_GET);
//$_SESSION["menuRedComercial"]=$dataResult;
//echo json_encode($dataResult);
//exit(0);

if(isset($_SESSION["menuRedComercial"])){
	echo json_encode($_SESSION["menuRedComercial"]);
	exit(0);
}
//$dataPost=json_decode(file_get_contents('php://input'),true);
include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.MenuRed.php");
$getMenu=new MenuRed($db);
$getMenu->tipoRed=3;//3:FICHA RED COMERCIAL
$resultGetMenu=$getMenu->getListCategory();
$category=[];
$subCategory=[];
function cleanText($string)
{
	$string = trim($string);
	$string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),$string);
	$string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),$string);
	$string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),$string);
	$string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),$string);
	$string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),$string);
	$string = str_replace(array('ñ','Ñ','ç','Ç'),array('n','N','c','C',),$string);
	//Esta parte se encarga de eliminar cualquier caracter extraño
	//$string = str_replace(array("\\", "¨", "º", "-", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/","(", ")", "?", "'", "¡","¿", "[", "^", "<code>", "]","+", "}", "{", "¨", "´",">", "< ", ";", ",", ":",".", " "),'',$string);
	$string = str_replace(array("\\", "¨", "º", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/","(", ")", "?", "'", "¡","¿", "[", "^", "<code>", "]","+", "}", "{", "¨", "´",">", "< ", ";", ",", ":",".", " "),'',$string);
	return $string;
}
while($row=$resultGetMenu["code"]->fetch(PDO::FETCH_ASSOC)){
	$url=strtolower(utf8_encode($row["nombre_categoria"]));
	$urlName=str_replace(" ", "-", $url);
	$urlClear=cleanText($urlName);
	$urlName=str_replace(" ","-",$urlClear);
	$category[]=array("url_name"=>$urlName,"name_category"=>utf8_encode($row["nombre_categoria"]),"id_category"=>$row["id_categoria"],"subcategory"=>"promociones");
}

$resultGetMenuSubCategory=$getMenu->getSubCategory();
while($row1=$resultGetMenuSubCategory["code"]->fetch(PDO::FETCH_ASSOC)){
	if(!in_array($row1["id_categoria"], $subCategory)){
		$subCategory[]=$row1["id_categoria"];
	}
}

foreach($category as $key=>$data1){
	$getMenu->idCategoria=$data1["id_category"];
	$resultNumberPromotions=$getMenu->numberOfPromotions();
	$row2=$resultNumberPromotions["code"]->fetch(PDO::FETCH_ASSOC);
	$totalRecords=20;
	$numberPage=$row2["numberPromotions"]/$totalRecords;
	$totalPage=ceil($numberPage);
	$category[$key]["numberOfPromotions"]=$row2["numberPromotions"];
	$category[$key]["totalPage"]=$totalPage;
	foreach ($subCategory as $data2){
		if($data1["id_category"]==$data2){
			$category[$key]["subcategory"]="categoria";
		}
	}
}
$dataResult=array("msg"=>$resultGetMenuSubCategory["msg"],"datosapi"=>$category);
$_SESSION["menuRedComercial"]=$dataResult;
echo json_encode($dataResult);
exit(0);