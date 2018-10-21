<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 30/08/2018
 * Time: 12:03 PM
 */
session_start();
include_once("../controller/database2.php");
$database=new Database();
$db=$database->getConnection();
include_once("../model/class.MedicalDirectory.php");
list($id_locality,$id_specialty)=explode("-",$_GET["ls"]);
$searchLocationSpecialty=new MedicalDirectory($db);
$searchLocationSpecialty->id_specialty=$id_specialty;
$searchLocationSpecialty->id_location=$id_locality;
$resultSearchLocationSpecialty=$searchLocationSpecialty->searchLocationSpecialty();
$searchResult=[];
function cleanTagsHtml($textToClean){
	$resultClean=utf8_encode($textToClean);
	$resultClean=strip_tags($resultClean);
	$resultClean=str_replace("\r\n",'', $resultClean);
	return $resultClean;
}
while($row=$resultSearchLocationSpecialty["code"]->fetch(PDO::FETCH_ASSOC)){
	$name=cleanTagsHtml($row["Nombre"]);
	$branchOffices=cleanTagsHtml($row["sucursal"]);
	$address=cleanTagsHtml($row["Direccion"]);
	$address=html_entity_decode($address, null, 'utf-8');
	$searchResult[]=array("id"=>$row["idPromo"],"name"=>utf8_encode($row["Nombre"]),"branchOffices"=>$branchOffices,"address"=>$address,"image"=>utf8_encode($row["imagen"]),"specialty"=>utf8_encode($row["Especialidad"]));
}
$dataResult=array("msg"=>$resultSearchLocationSpecialty["msg"],"locationStorage"=>$searchResult);
echo json_encode($dataResult);
exit(0);