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
//ob_implicit_flush(true);
//ob_end_flush();

$idpromotion=$_GET["n"]; //2,77,1126 $_GET["n"]


//$dataPost=json_decode(file_get_contents('php://input'),true);
//$dataResult=array("msg"=>"continue","datosapi"=>$_GET["n"]);
//echo json_encode($dataResult);
//exit;



//echo json_encode($_GET);
//exit;



/*if(isset($_SESSION["storageDataPromotion"][$_GET["n"]])){
	echo json_encode($_SESSION["storageDataPromotion"][$_GET["n"]]);
	exit(0);
}*/

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

/*function extraerURLs($cadena){
	$regex = '/https?\:\/\/[^\"]+/i';
	preg_match_all($regex, $cadena, $partes);
	return ($partes[0][0]);
}*/

include_once("../controller/database2.php");
$database=new Database;
$db=$database->getConnection();
include_once("../model/class.MedicalDirectory.php");
$getPromotionCurrent=new MedicalDirectory($db);
//$getPromotionCurrent->idPromotion=$_GET["n"];
$getPromotionCurrent->idPromotion=$idpromotion;
$resultGetPromotionCurrent=$getPromotionCurrent->getPromotionForIdNumber();


//$dataResult=array("msg"=>"continue","datosapi"=>$resultGetPromotionCurrent);
//echo json_encode($dataResult);
//exit;



$result=array();
//$path="https://servicioshuman.mx";
$row=$resultGetPromotionCurrent["code"]->fetch(PDO::FETCH_ASSOC);
$getPromotionCurrent->idEspecialidad=$row["id_especialidad"];
//$dataResult=array("msg"=>utf8_encode($row["Nombre"]),"datosapi"=>utf8_encode($row["Direccion"]));
//echo json_encode($dataResult);
//exit;

	//$image64=base64_encode($row["bk_img"]);
	$nameSocio=utf8_encode($row["Nombre"]);

	if(isset($row["fecha_update"])) {
		$dateAndTime = $row["fecha_update"];
		list( $currentDate, $currentTime ) = explode( " ", $dateAndTime );
		$dateToText = fechaCastellano( $currentDate );
	}else{
		$dateToText="";
	}
/*(isset($row["promo_pdf"]))?$filePromo=1:$filePromo=0;
(isset($row["suc_pdf"]))?$fileSucursales=1:$fileSucursales=0;
($filePromo==1)?$linkPdfPromo='https://www.servicioshuman.mx/tarjetasmb/informacion-'.utf8_encode($row["socio"]).'-'.$row["idcp_pdf_ficha"]:$linkPdfPromo="***";
($fileSucursales==1)?$linkSucursalesPromo='https://www.servicioshuman.mx/tarjetasmb/sucursales-'.utf8_encode($row["socio"]).'-'.$row["idcp_pdf_ficha"]:$linkSucursalesPromo="***";*/

$textPromotion=$row["Promocion_human"];


//$textPromotion=preg_replace('/\r\n|\r|\n\r|\n/m',' ',$textPromotion);


$schedules=cleanHtmlAndTags($row["Horarios_atencion"]);
($row["pagina_web"]!="")?$webPage=checkUrl($row["pagina_web"]):$webPage="***";
//($row["facebook"]!="")?$facebook=checkUrl($row["facebook"]):$facebook="***";
//($row["twitter"]!="")?$twitter=checkUrl($row["twitter"]):$twitter="***";

($row["Mail"]!="")?$email=$row["Mail"]:$email="***";
($row["Mail"]!="N/A")?$email=$row["Mail"]:$email="***";
$telefono=$row["Telefonos"];
(isset($row["Direccion"]))?$textDireccion=$row["Direccion"]:$textDireccion="***";
(isset($row["Horarios_atencion"]) && $row["Horarios_atencion"]!="")?$scheduleText=$row["Horarios_atencion"]:$scheduleText="***";
	//$htmlImage='<img src="data:image/png;base64, '.$image64.'" alt="'.$nameSocio.'" />';




/*###################################################start show imagen*/
$getDataEspecialidad=$getPromotionCurrent->especialidadXid();
$dataImgEspe=$getDataEspecialidad["code"]->fetch(PDO::FETCH_ASSOC);
$externalPath="https://servicioshuman.mx";//FichaMedica/DraClaudiaSernaOjeda.png
$arrayPath=explode("/", $row["imgficha"]);
$indexArrayPath=count($arrayPath);
($indexArrayPath==2)?$pathImage=$externalPath."/promo-medicas/".$dataImgEspe["imagenesp"]:$pathImage=$externalPath."/promo-medicas/".$row["imgficha"];
$result[]=array("telefono"=>utf8_encode($telefono),
                "email"=>utf8_encode($email),
                "webPage"=>utf8_encode($webPage),
                "schedulesText"=>utf8_encode($scheduleText),
                "promocionTexto"=>utf8_encode($textPromotion),
                "direccionTexto"=>utf8_encode($textDireccion),
                "namesocio"=>$nameSocio,
                "currentDate"=>utf8_encode($dateToText),
				"image"=>utf8_encode($pathImage)
);
$dataResult=array("msg"=>$resultGetPromotionCurrent["msg"],"datosapi"=>$result);
$idCurrentArray=$idpromotion;
$_SESSION["storageDataDirMedic"][$idCurrentArray]=$dataResult;
echo json_encode($dataResult);
exit;