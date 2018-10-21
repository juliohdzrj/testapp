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
//$dataResult=array("msg"=>"msg1","datosapi"=>$_GET);
//$_SESSION["subMenuRedComercial"]=$dataResult;
//echo json_encode($dataResult);
//exit(0);

if(isset($_SESSION["subMenuRedMedical"])){
	echo json_encode($_SESSION["subMenuRedMedical"]);
	exit(0);
}
$dataPost=json_decode(file_get_contents('php://input'),true);
include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.MenuRed.php");
$getMenu=new MenuRed($db);
$getMenu->idCategoria=$_GET["i"];//3:FICHA RED COMERCIAL $dataPost["id"];
$resultGetMenu=$getMenu->getListSubCategory();
$subCategory=[];
function normaliza ($cadena){
	$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$cadena = utf8_decode($cadena);
	$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	$cadena = strtolower($cadena);
	return utf8_encode($cadena);
}
while($row=$resultGetMenu["code"]->fetch(PDO::FETCH_ASSOC)){
	$url=strtolower($row["nombre_subcategoria"]);
	$urlClear=normaliza($url);
	$urlName=str_replace(" ","-",$urlClear);
	$subCategory[]=array("url_name"=>$urlName,"name_subcategory"=>$row["nombre_subcategoria"],"id_category"=>$row["id_categoria"],"id_subcategory"=>$row["id_subcategoria"]);
}

foreach($subCategory as $key=>$data1){
	$getMenu->idSubCategoria=$data1["id_subcategory"];
	$resultNumberPromotions=$getMenu->numberOfPromotionsSubCategory();
	$row2=$resultNumberPromotions["code"]->fetch(PDO::FETCH_ASSOC);
	$totalRecords=10;
	$numberPage=$row2["numberPromotionSubcategory"]/$totalRecords;
	$totalPage=ceil($numberPage);
	$subCategory[$key]["numberPromotionsSubCategory"]=$row2["numberPromotionSubcategory"];
	$subCategory[$key]["totalPage"]=$totalPage;
}

$dataResult=array("msg"=>$resultGetMenu["msg"],"datosapi"=>$subCategory);
$_SESSION["subMenuRedMedical"]=$dataResult;
echo json_encode($dataResult);
exit(0);