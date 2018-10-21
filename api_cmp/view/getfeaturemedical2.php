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
session_start();
set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();

$idpromotion=78; //78, 118, 189
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
function cleanHtmlAndTags($textHtml){
	$cleanText=strip_tags($textHtml);
	$cleanText=html_entity_decode($cleanText);
	return $cleanText;
}
function checkUrl($textUrl){
	//CHECK URL POTOCOL, IF IT DOES NOT EXIST, PLACE HTTP PROTOCOL
	$porciones=preg_split("(\/\/)", $textUrl);
	if(count($porciones)>1){
		return $textUrl;
	}else{
		return "http://".$textUrl;
	}
}
include_once("../controller/database2.php");
$database=new Database;
$db=$database->getConnection();
include_once("../model/class.MedicalDirectory.php");
$getPromotionCurrent=new MedicalDirectory($db);
$getPromotionCurrent->idPromotion=$idpromotion;
$resultGetPromotionCurrent=$getPromotionCurrent->getPromotionForIdNumber();
$result=array();
$row=$resultGetPromotionCurrent["code"]->fetch(PDO::FETCH_ASSOC);

//echo ("<pre>");
//print_r($resultGetPromotionCurrent);
//exit;
$getPromotionCurrent->idEspecialidad=$row["id_especialidad"];
$nameSocio=utf8_encode($row["Nombre"]);
	if(isset($row["fecha_update"])) {
		$dateAndTime = $row["fecha_update"];
		list( $currentDate, $currentTime ) = explode( " ", $dateAndTime );
		$dateToText = fechaCastellano( $currentDate );
	}else{
		$dateToText="";
	}
$textPromotion=$row["Promocion_human"];
$schedules=cleanHtmlAndTags($row["Horarios_atencion"]);
($row["pagina_web"]!="")?$webPage=checkUrl($row["pagina_web"]):$webPage="***";
($row["Mail"]!="")?$email=$row["Mail"]:$email="***";
$telefono=$row["Telefonos"];
(isset($row["Direccion"]))?$textDireccion=$row["Direccion"]:$textDireccion="***";
(isset($row["Horarios_atencion"]) && $row["Horarios_atencion"]!="")?$scheduleText=$row["Horarios_atencion"]:$scheduleText="***";
/*###################################################start show imagen*/
$getDataEspecialidad=$getPromotionCurrent->especialidadXid();
$dataImgEspe=$getDataEspecialidad["code"]->fetch(PDO::FETCH_ASSOC);
$storagePath="../../images/directory/";
$externalPath="https://servicioshuman.mx";//FichaMedica/DraClaudiaSernaOjeda.png
$nombre_imagen_generica=$externalPath."/promo-medicas/".$dataImgEspe["imagenesp"];
$nombre_imagen_ficha=$externalPath."/promo-medicas/".$row["imgficha"];
$arrayPath=explode("/", $row["imgficha"]);
$indexArrayPath=count($arrayPath);
$namePathImage=$storagePath.$dataImgEspe["imagenesp"];
$externalImage=$nombre_imagen_generica;
$nameImage=$dataImgEspe["imagenesp"];
if($indexArrayPath<2){
$file=$nombre_imagen_ficha;
$file_headers=@get_headers($file);
(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')?$namePathImage=$storagePath.$dataImgEspe["imagenesp"]:$namePathImage=$storagePath.$row["imgficha"];
(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')?$externalImage=$nombre_imagen_generica:$externalImage=$nombre_imagen_ficha;
(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')?$nameImage=$dataImgEspe["imagenesp"]:$nameImage=$row["imgficha"];
}
if (!file_exists($namePathImage)) {
	$ch = curl_init($externalImage);
	$fp = fopen($storagePath.$nameImage, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}
/*end show imagen*/

$result[]=array("telefono"=>utf8_encode($telefono),
                "email"=>utf8_encode($email),
                "webPage"=>utf8_encode($webPage),
                "schedulesText"=>utf8_encode($scheduleText),
                "promocionTexto"=>utf8_encode($textPromotion),
                "direccionTexto"=>utf8_encode($textDireccion),
                "namesocio"=>$nameSocio,
                "currentDate"=>utf8_encode($dateToText)
);

$dataResult=array("msg"=>$resultGetPromotionCurrent["msg"],"datosapi"=>$result);
$idCurrentArray=$idpromotion;
$_SESSION["storageDataDirMedic"][$idCurrentArray]=$dataResult;
echo json_encode($dataResult);
exit;