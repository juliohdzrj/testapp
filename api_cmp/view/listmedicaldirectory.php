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
//$dataPost=json_decode(file_get_contents('php://input'),true);
//$dataResult=array("msg"=>"continue","datosapi"=>$_GET);
//echo json_encode($dataResult);
//exit;
/*function fechaCastellano ($fecha) {
	$fecha = substr($fecha, 0, 10);
	$numeroDia = date('d', strtotime($fecha));
	$dia = date('l', strtotime($fecha));
	$mes = date('F', strtotime($fecha));
	$anio = date('Y', strtotime($fecha));
	$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$nombredia = str_replace($dias_EN, $dias_ES, $dia);
	$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
	//return $nombredia." | ".$nombreMes." ".$numeroDia." | ".$anio;
	return $nombreMes." ".$numeroDia." | ".$anio;
}
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
}*/

/*if(isset($_SESSION["dataResult"])){
	echo json_encode($_SESSION["dataResult"]);
	exit(0);
}*/

/*
public static function TildesHtml($cadena)
{
	return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
		array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
			"&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);
}

$texto = htmlentities("ejemplo á é í ó ú ñ", ENT_QUOTES, "UTF-8");
$texto = html_entity_decode("ejemplo &ntilde;", ENT_QUOTES, "UTF-8");
*/
session_start();
if(isset($_SESSION["predictionList"])){
	echo json_encode($_SESSION["predictionList"]);
	exit(0);
}
include_once("../controller/database2.php");
$database=new Database();
$db=$database->getConnection();
include_once("../model/class.MedicalDirectory.php");
$getListMedicalDirectory=new MedicalDirectory($db);
$resultMedicalDirectory=$getListMedicalDirectory->getDataNameEnterprise();
$storageEnterprise=array();
$storageName=array();
$storageSpecialty=array();
$storageSubSpecialty=array();
$storageData=array();

while($row=$resultMedicalDirectory["code"]->fetch(PDO::FETCH_ASSOC)){
	//array("number"=>$row["idMedica"],"enterprise"=>utf8_encode($row["Empresa"]),"name"=>utf8_encode($row["Nombre"]));
	$enterprise=trim($row["Empresa"]);
	$name=trim($row["Nombre"]);
	$specialty=trim($row["Especialidad"]);
	$subspecialty=trim($row["Subespecialidad"]);
	$state=trim($row["Delegacion"]);
	$delegation=trim($row["Estado"]);
	$storageEnterprise[]=utf8_encode($enterprise);
	$storageName[]=utf8_encode($name);
	$storageSpecialty[]=utf8_encode($specialty);
	$storageSubSpecialty[]=utf8_encode($subspecialty);
	$storageState[]=utf8_encode($state);
	$storageDelegation[]=utf8_encode($delegation);
}

$enterpriseNotRepeated=array();
foreach ($storageEnterprise as $dataEnterprise){
	if (!in_array($dataEnterprise, $enterpriseNotRepeated)) {
		$enterpriseNotRepeated[]=$dataEnterprise;
	}
}

$nameNotRepeated=array();
foreach ($storageName as $dataName){
	if (!in_array($dataName, $nameNotRepeated)) {
		$nameNotRepeated[]=$dataName;
	}
}

$specialtyNotRepeated=array();
foreach ($storageSpecialty as $dataSpecialty){
	if (!in_array($dataSpecialty, $specialtyNotRepeated)){
		$specialtyNotRepeated[]=$dataSpecialty;
	}
}

$subSpecialtyNotRepeated=array();
foreach ($storageSubSpecialty as $dataSubSpecialty){
	if (!in_array($dataSubSpecialty, $subSpecialtyNotRepeated)){
		$subSpecialtyNotRepeated[]=$dataSubSpecialty;
	}
}

$stateNotRepeated=array();
foreach ($storageState as $dataStorageState){
	if (!in_array($dataStorageState, $stateNotRepeated)){
		$stateNotRepeated[]=$dataStorageState;
	}
}

$delegationNotRepeated=array();
foreach ($storageDelegation as $dataDelegation){
	if (!in_array($dataDelegation, $delegationNotRepeated)){
		$delegationNotRepeated[]=$dataDelegation;
	}
}
/*#################################################################*/
foreach ($enterpriseNotRepeated as $data1){
	if (!in_array($data1, $storageData)){
		$storageData[]=$data1;
	}
}
foreach ($nameNotRepeated as $data2){
	if (!in_array($data2, $storageData)){
		$storageData[]=$data2;
	}
}
foreach ($specialtyNotRepeated as $data3){
	if (!in_array($data3, $storageData)){
		$storageData[]=$data3;
	}
}
foreach ($subSpecialtyNotRepeated as $data4){
	if (!in_array($data4, $storageData)){
		$storageData[]=$data4;
	}
}
foreach ($stateNotRepeated as $data5){
	if (!in_array($data5, $storageData)){
		$storageData[]=$data5;
	}
}
foreach ($delegationNotRepeated as $data6){
	if (!in_array($data6, $storageData)){
		$storageData[]=$data6;
	}
}

$dataResult=array("msg"=>$resultMedicalDirectory["msg"],"datosapi"=>$storageData);
$_SESSION["predictionList"]=$dataResult;
//echo count($storageData);
//print_r ($dataResult);
echo json_encode($dataResult);
exit(0);