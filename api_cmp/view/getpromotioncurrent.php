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
//$dataPost=json_decode(file_get_contents('php://input'),true);
//$dataResult=array("msg"=>"continue","datosapi"=>$_GET["n"]);
//echo json_encode($dataResult);
//exit;
if(isset($_SESSION["storageDataPromotion"][$_GET["n"]])){
	echo json_encode($_SESSION["storageDataPromotion"][$_GET["n"]]);
	exit(0);
}

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

include_once("../controller/databaseapi.php");
$database=new Databaseapi;
$db=$database->getConnection();
include_once("../model/class.ListPromotions.php");
$getPromotionCurrent=new ListPromotions($db);
//$getPromotionCurrent->idPromotion=$_GET["n"];
$getPromotionCurrent->idPromotion=$_GET["n"];
$resultGetPromotionCurrent=$getPromotionCurrent->getPromotionForNumber();
//$dataResult=array("msg"=>"continue","datosapi"=>$resultGetPromotionCurrent);
//echo json_encode($dataResult);
//exit;
$result=array();
$path="../../images/promocion";
$row=$resultGetPromotionCurrent["code"]->fetch(PDO::FETCH_ASSOC);
	$image64=base64_encode($row["bk_img"]);
	$nameSocio=utf8_encode($row["socio"]);
	$dateAndTime=$row["fecha_update"];
list($currentDate,$currentTime)=explode(" ",$dateAndTime);
$dateToText=fechaCastellano($currentDate);
(isset($row["promo_pdf"]))?$filePromo=1:$filePromo=0;
(isset($row["suc_pdf"]))?$fileSucursales=1:$fileSucursales=0;


($filePromo==1)?$linkPdfPromo='https://www.servicioshuman.mx/tarjetasmb/informacion-'.utf8_encode($row["socio"]).'-'.$row["idcp_pdf_ficha"]:$linkPdfPromo="***";
($fileSucursales==1)?$linkSucursalesPromo='https://www.servicioshuman.mx/tarjetasmb/sucursales-'.utf8_encode($row["socio"]).'-'.$row["idcp_pdf_ficha"]:$linkSucursalesPromo="***";



//split url
/*($row["pagina_web"]!="")?$paginaweb=split("(http:\/\/www.|https:\/\/www.|http:\/\/|https:\/\/|www.)", $row["pagina_web"]):$paginaweb="***";*/
//end split url


//$urlTextPromotion=extraerURLs($row["promocion_texto"]);
//$textPromotion=cleanHtmlAndTags($row["promocion_texto"]);
$textPromotion=$row["promocion_texto"];


//$textPromotion=preg_replace('/\r\n|\r|\n\r|\n/m',' ',$textPromotion);


$schedules=cleanHtmlAndTags($row["horarios"]);
($row["pagina_web"]!="")?$webPage=checkUrl($row["pagina_web"]):$webPage="***";
($row["facebook"]!="")?$facebook=checkUrl($row["facebook"]):$facebook="***";
($row["twitter"]!="")?$twitter=checkUrl($row["twitter"]):$twitter="***";
//$webPage=checkUrl($row["pagina_web"]);
//$facebook=checkUrl($row["facebook"]);
//$twitter=checkUrl($row["twitter"]);
($row["email"]!="")?$email=$row["email"]:$email="***";
$telefono=$row["telefono"];
(isset($row["direccion_texto"]))?$textDireccion=$row["direccion_texto"]:$textDireccion="***";
(isset($row["horarios"]) && $row["horarios"]!="")?$scheduleText=$row["horarios"]:$scheduleText="***";
	//$htmlImage='<img src="data:image/png;base64, '.$image64.'" alt="'.$nameSocio.'" />';






$urlName=trim($row["socio"]);
$urlName=str_replace(" ", "-", utf8_encode($urlName));
$urlName=cleanText($urlName);


list($typeFile,$extensionFile)=explode("/", $row["tipo_bkimg"]);
$filename=$path."/".$urlName.".".$extensionFile;
if (!file_exists($filename)) {
	file_put_contents($filename,$row["bk_img"]);
}












$result[]=array("telefono"=>utf8_encode($telefono),
                "email"=>utf8_encode($email),
                "twitter"=>utf8_encode($twitter),
                "facebook"=>utf8_encode($facebook),
                "webPage"=>utf8_encode($webPage),
                "schedulesText"=>utf8_encode($scheduleText),
                "promocionTextoUrl"=>$linkPdfPromo,
                "promocionTexto"=>utf8_encode($textPromotion),
                "direccionTexto"=>utf8_encode($textDireccion),
                "sucursalesUrl"=>$linkSucursalesPromo,
                "namesocio"=>$nameSocio,
                "image"=>$image64,
                "currentDate"=>$dateToText,
	);

$dataResult=array("msg"=>$resultGetPromotionCurrent["msg"],"datosapi"=>$result);
$idCurrentArray=$_GET["n"];
$_SESSION["storageDataPromotion"][$idCurrentArray]=$dataResult;
echo json_encode($dataResult);
exit;

/*$resultGetDataCategory=$getListPromotions->getDataCategory();
$row=$resultGetDataCategory["code"]->fetch(PDO::FETCH_ASSOC);
$numberPromotions=$getListPromotions->promotionsForCategory();
$row2=$numberPromotions["code"]->fetch(PDO::FETCH_ASSOC);
$totalRecords=10;
$numberPage=$row2["promotionsforcategory"]/$totalRecords;
$totalPage=ceil($numberPage);
$intervalSelectLimit=array();
for($i=0;$i<$totalPage;$i++){
	$intervalCurrent=$i*$totalRecords;
	$intervalSelectLimit[]=$intervalCurrent;
}
$dataResult=array("msg"=>$resultGetDataCategory["msg"],"datosapi"=>array("nameCategory"=>utf8_encode($row["nombre_categoria"]),"idCategory"=>$dataPost["n"],"startLimit"=>$intervalSelectLimit,"totalpage"=>$totalPage));
echo json_encode($dataResult);
exit;*7