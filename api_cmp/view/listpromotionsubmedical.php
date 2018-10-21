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
set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();
/*$dataResult=array("msg"=>"continue","datosapi"=>$_GET);
echo json_encode($dataResult);
exit;*/
function fechaCastellano ($fecha) {
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
}


include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.ListPromotions.php");
$getListPromotions=new ListPromotions($db);
$getListPromotions->tipoRed=4;//4:FICHA RED MEDICA
$getListPromotions->idCategoria=$_GET["n"];
$getListPromotions->idSubCategoria=$_GET["sn"];
$getListPromotions->startRange=$_GET["r"];
$resultGetListPromotions=$getListPromotions->getListSubPromotions();
$resultDataCategory=$getListPromotions->getDataCategory();
$dataNameCategoty=$resultDataCategory["code"]->fetch(PDO::FETCH_ASSOC);
$result=array();
$path="../../images/logos";
while($row=$resultGetListPromotions["code"]->fetch(PDO::FETCH_ASSOC)){
	$image64=base64_encode($row["cp_img"]);
	$nameSocio=utf8_encode($row["socio"]);
	$dateAndTime=$row["fecha_update"];
	list($currentDate,$currentTime)=explode(" ",$dateAndTime);
	$dateToText=fechaCastellano($currentDate);
	$urlName=trim($row["socio"]);
	$urlName=str_replace(" ", "-", utf8_encode($urlName));
	$urlName=cleanText($urlName);
	list($typeFile,$extensionFile)=explode("/", $row["tipo_cpimg"]);
	$filename=$path."/".$urlName.".".$extensionFile;
	if (!file_exists($filename)) {
		file_put_contents($filename,$row["cp_img"]);
	}

	//$htmlImage='<img src="data:image/png;base64, '.$image64.'" alt="'.$nameSocio.'" />';
	//$result[]=array("namesocio"=>$nameSocio,"image"=>$image64,"colordocument"=>utf8_encode($row["colorpdf"]));
	$result[]=array("urlName"=>"descuento-".strtolower($urlName)."?n=".$row["idcp_pdf_ficha"]."&s=".utf8_encode($dataNameCategoty["nombre_categoria"])."-".strtolower($nameSocio),
	                "namesocio"=>$nameSocio,
	                "image"=>$image64,
	                "currentDate"=>$dateToText,
	                "idcurrent"=>$row["idcp_pdf_ficha"]);
}
$dataResult=array("msg"=>$resultGetListPromotions["msg"],"datosapi"=>$result);
echo json_encode($dataResult);
exit;