<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 24/07/2018
 * Time: 06:26 PM
 */
set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();
$headers = apache_request_headers();
if($headers["Authorization"]!="35f13ff714ce50c37baccead2e5a90c5="){
	exit(0);
}
//$dataPost=json_decode(file_get_contents('php://input'),true);
include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.MenuRed.php");
$getMenu=new MenuRed($db);
$getMenu->tipoRed=4;//3:FICHA RED COMERCIAL
$resultGetMenu=$getMenu->getListCategory();
$category=[];
$subCategory=[];



while($row=$resultGetMenu["code"]->fetch(PDO::FETCH_ASSOC)){
	$category[]=array("name_category"=>utf8_encode($row["nombre_categoria"]),"id_category"=>$row["id_categoria"]);
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
	$category[$key]["numberOfPromotions"]=$row2["numberPromotions"];
	foreach ($subCategory as $data2){
		if($data1["id_category"]==$data2){
			$category[$key]["subcategory"]="idsubcat";
		}
	}
}

$dataResult=array("msg"=>$resultGetMenuSubCategory["msg"],"datosapi"=>$category);
echo json_encode($dataResult);
exit;