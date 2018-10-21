<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 24/07/2018
 * Time: 06:26 PM
 */
/*$headers = apache_request_headers();
if($headers["Authorization"]!="35f13ff714ce50c37baccead2e5a90c5="){
	exit(0);
}*/
$dataPost=json_decode(file_get_contents('php://input'),true);
include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.ListPromotions.php");
$getListPromotions=new ListPromotions($db);
$getListPromotions->tipoRed=3;//3:FICHA RED COMERCIAL
$getListPromotions->idCategoria=$dataPost["idCategoryCurrent"];
$getListPromotions->startRange=$dataPost["totalPageCurrent"];
$resultListLimit=$getListPromotions->getListPromotions();
$dataListLimitCurrent=array();
while($row=$resultListLimit["code"]->fetch(PDO::FETCH_ASSOC)){
	$dataListLimitCurrent[]=array("namesocio"=>utf8_encode($row["socio"]));
}
$dataResult=array("msg"=>$resultGetDataCategory["msg"],"datosapi"=>$dataListLimitCurrent);
echo json_encode($dataResult);
exit;