<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 24/07/2018
 * Time: 06:26 PM
 */
/*session_start();
if(isset($_SESSION["predictionList"])){
	echo json_encode(array("res"=>$_SESSION["predictionList"]));
	exit(0);
}*/

/*$dataPost["email"],$dataPost["name"],$dataPost["nameUser"],$dataPost["lastName"],$dataPost["pass"]*/
include_once("regexpfunction.php");
$dataPost=json_decode(file_get_contents('php://input'),true);
$email_set=trim($dataPost["email"]);
$name_set=trim($dataPost["name"]);
$nameUser_set=trim($dataPost["nameUser"]);
$lastName_set=trim($dataPost["lastName"]);
$pass1_set=trim($dataPost["pass"]);
$pass2_set=trim($dataPost["passRepeat"]);
$msg="";
$regexpEdo=verify_email($email_set);
($regexpEdo==false)?$msg="Coloca un correo electrónico valido":$msg="continue";
$regexpEdo=verify_text($name_set);
($regexpEdo==false)?$msg="Coloca un nombre valido":$msg="continue";
$regexpEdo=verify_text($lastName_set);
($regexpEdo==false)?$msg="Coloca un apellido valido":$msg="continue";
$regexpEdo=verify_name_user($nameUser_set);
($regexpEdo==false)?$msg="Coloca un nombre de usuraio valido":$msg="continue";
if($pass1_set===$pass2_set){
	$regexpEdo=verify_password($pass1_set);
	($regexpEdo==false)?$msg="Coloca una contraseña valida":$msg="continue";
}else{
	$msg="Las contraseñas no son iguales";
}

/*INSERT DATA USER*/
include_once("../controller/databaseapi.php");
$database=new Databaseapi();
$db=$database->getConnection();
include_once("../model/class.Register.php");
$registerUser=new Register($db);
$resRegisterUser=$registerUser->insertDataUser=array("emailSet"=>$email_set,"nameSet"=>$name_set,"lastnameSet"=>$lastName_set,"nameUser"=>$nameUser_set,"passwordSet"=>md5($pass1_set));

/*END INSERT DATA USER*/

$dataResult=array("msg"=>$msg,"datosapi"=>$dataPost,"edoval"=>$resRegisterUser);
echo json_encode($dataResult);
exit;






set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();






function NoTilde($cadena)
{
	return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
		array("a","e","i","o","u","n","A","E","I","O","U","Ñ"), $cadena);
}



function TildesHtml($cadena)
{
	return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
		array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
			"&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);
}

function TildesHtmlInvert($cadena)
{
	return str_replace(array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
		"&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),$cadena);
}

$prepositions=array("a","ante","bajo","cabe","con","contra","de","desde","durante","en","entre","hacia","hasta","mediante","para","por","segun","según","sin","so","sobre","tras","versus","vía","via");
$notilde=NoTilde($_GET["t"]);
$stringText=trim($notilde);
$stringText1=trim($_GET["t"]);
$stringText2=trim($_GET["t"]);
$stringText2=utf8_decode($_GET["t"]);
$concordanciaAmplia=str_replace(" ","%",$stringText);

$stringWords=str_replace(" ","-",$stringText);
$arrayWords=explode("-",$stringWords);
$arrayWords=array_diff($arrayWords,$prepositions);
$textTildeHtml=TildesHtml($stringText);

$textTildeHtmlAmplia=TildesHtml($concordanciaAmplia);
//$textTildeHtml=htmlentities($stringText, ENT_QUOTES, "UTF-8");
/* BUILT QUERY
 * ###############################################################################*/
$queryRequest.="CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtmlAmplia."%' AND act_sup=1 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtml."%' AND act_sup=1 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$stringText1."%' AND act_sup=1 LIMIT ".$_GET["sr"].",20;";



/*$queryRequest.="CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtmlAmplia."%'
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$concordanciaAmplia."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtml."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$stringText."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$stringText2."%";*/

/*$queryRequest.=" OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtmlAmplia."'
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$concordanciaAmplia." 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$textTildeHtml." 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$stringText." 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '%".$stringText2."";

$queryRequest.=" OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '".$textTildeHtmlAmplia."%' 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '".$concordanciaAmplia."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '".$textTildeHtml."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '".$stringText."% 
OR CONCAT_WS(Promocion_human,Especialidad,Subespecialidad,Empresa,Nombre,Sucursal,Direccion,Delegacion,Estado,Horarios_atencion) LIKE '".$stringText2."%";*/

/*$queryRequest.="Nombre LIKE '%".$concordanciaAmplia."%' AND act_sup=1
OR Especialidad LIKE '%".$concordanciaAmplia."%' AND act_sup=1 
OR Subespecialidad LIKE '%".$concordanciaAmplia."%' AND act_sup=1 
OR Estado LIKE '%".$concordanciaAmplia."%' AND act_sup=1 
OR Sucursal LIKE '%".$concordanciaAmplia."%' AND act_sup=1 
OR Empresa LIKE '%".$concordanciaAmplia."%' AND act_sup=1 
OR Direccion LIKE '%".$textTildeHtmlAmplia."%' AND act_sup=1 
OR Delegacion LIKE '%".$concordanciaAmplia."%' AND act_sup=1 OR Promocion_human LIKE '%".$concordanciaAmplia."%' AND act_sup=1";


$queryRequest.=" OR Nombre='".$stringText2."' AND act_sup=1 
OR Especialidad='".$stringText2."' AND act_sup=1 
OR Subespecialidad='".$stringText2."' AND act_sup=1 
OR Estado='".$stringText2."' AND act_sup=1 
OR Sucursal='".$stringText2."' AND act_sup=1 
OR Empresa='".$stringText2."' AND act_sup=1 
OR Direccion='".$textTildeHtml."' AND act_sup=1 
OR Delegacion='".$stringText2."' AND act_sup=1";

$queryRequest.=" OR Nombre LIKE '%".$stringText2."' AND act_sup=1 
OR Especialidad LIKE '%".$stringText2."' AND act_sup=1 
OR Subespecialidad LIKE '%".$stringText2."' AND act_sup=1 
OR Estado LIKE '%".$stringText2."' AND act_sup=1 
OR Sucursal LIKE '%".$stringText2."' AND act_sup=1 
OR Empresa LIKE '%".$stringText2."' AND act_sup=1 
OR Direccion LIKE '%".$textTildeHtml."' AND act_sup=1 
OR Delegacion LIKE '%".$stringText2."' AND act_sup=1";


$queryRequest.=" OR Nombre LIKE '".$stringText2."%' AND act_sup=1 
OR Especialidad LIKE '".$stringText2."%' AND act_sup=1 
OR Subespecialidad LIKE '".$stringText2."%' AND act_sup=1 
OR Estado LIKE '".$stringText2."%' AND act_sup=1 
OR Sucursal LIKE '".$stringText2."%' AND act_sup=1 
OR Empresa LIKE '".$stringText2."%' AND act_sup=1 
OR Direccion LIKE '".$textTildeHtml."%' AND act_sup=1 
OR Delegacion LIKE '".$stringText2."%' AND act_sup=1";


$queryRequest.=" OR Nombre LIKE '%".$stringText2."%' AND act_sup=1 
OR Especialidad LIKE '%".$stringText2."%' AND act_sup=1 
OR Subespecialidad LIKE '%".$stringText2."%' AND act_sup=1 
OR Estado LIKE '%".$stringText2."%' AND act_sup=1 
OR Sucursal LIKE '%".$stringText2."%' AND act_sup=1 
OR Empresa LIKE '%".$stringText2."%' AND act_sup=1 
OR Direccion LIKE '%".$textTildeHtml."%' AND act_sup=1 
OR Delegacion LIKE '%".$stringText2."%' AND act_sup=1";*/

$queryRequest = str_replace("\r\n",'', $queryRequest);
/* END BUILT QUERY
 * ###############################################################################*/
include_once("../controller/database2.php");
$database=new Database();
$db=$database->getConnection();
include_once("../model/class.MedicalDirectory.php");
$searchMedicalDirectory=new MedicalDirectory($db);
$searchMedicalDirectory->whereQuery=$queryRequest;
//$searchMedicalDirectory->startRange=$_GET["sr"];
$resultSearchMedicalDirectory=$searchMedicalDirectory->searchText();

/*if($nrows==0){
	$queryRequest="";
	$queryRequest.="
Nombre LIKE '%".$stringText2."%' AND act_sup=1 
OR Especialidad LIKE '%".$stringText2."%' AND act_sup=1 
OR Subespecialidad LIKE '%".$stringText2."%' AND act_sup=1 
OR Estado LIKE '%".$stringText2."%' AND act_sup=1 
OR Sucursal LIKE '%".$stringText2."%' AND act_sup=1 
OR Empresa LIKE '%".$stringText2."%' AND act_sup=1 
OR Direccion LIKE '%".$textTildeHtml."%' AND act_sup=1 
OR Delegacion LIKE '%".$stringText2."%' AND act_sup=1";
	foreach ($arrayWords as $dataWord){
		if(count($dataWord)>4){
			break;
		}
		//$wordTildeHtml=htmlentities($dataWord, ENT_QUOTES, "UTF-8");
		$wordTildeHtml=TildesHtml($dataWord);
		$queryRequest.=" OR Nombre LIKE '%".$dataWord."%' AND act_sup=1 
OR Especialidad LIKE '%".$dataWord."%' AND act_sup=1 
OR Subespecialidad LIKE '%".$dataWord."%' AND act_sup=1 
OR Estado LIKE '%".$dataWord."%' AND act_sup=1 
OR Sucursal LIKE '%".$dataWord."%' AND act_sup=1 
OR Direccion LIKE '%".$wordTildeHtml."%' AND act_sup=1 
OR Delegacion LIKE '%".$dataWord."%' AND act_sup=1 
OR Empresa LIKE '%".$dataWord."%' AND act_sup=1";
	}

	$queryRequest = str_replace("\r\n",'', $queryRequest);
	$searchMedicalDirectory->whereQuery=$queryRequest;
	$resultSearchMedicalDirectory=$searchMedicalDirectory->searchText();
}*/

$searchResult=array();

function cleanTagsHtml($textToClean){
	$resultClean=utf8_encode($textToClean);
	$resultClean=strip_tags($resultClean);
	$resultClean=str_replace("\r\n",'', $resultClean);
	return $resultClean;
}

$nrows=$resultSearchMedicalDirectory["code"]->rowCount();

while($row=$resultSearchMedicalDirectory["code"]->fetch(PDO::FETCH_ASSOC)){
	$name=cleanTagsHtml($row["Nombre"]);
	$branchOffices=cleanTagsHtml($row["sucursal"]);
	$address=cleanTagsHtml($row["Direccion"]);
	$address=html_entity_decode($address, null, 'utf-8');
	//$address=TildesHtmlInvert($address);
	$searchResult[]=array("id"=>$row["idPromo"],"name"=>utf8_encode($row["Nombre"]),"branchOffices"=>$branchOffices,"address"=>$address,"image"=>utf8_encode($row["imagen"]),"specialty"=>utf8_encode($row["Especialidad"]));
}


$dataResult=array("msg"=>$resultSearchMedicalDirectory["msg"],"datosapi"=>$searchResult,"counter"=>$nrows,"msg2"=>$queryRequest);
//$_SESSION["predictionList"]=$dataResult;
//echo ("<pre>");
//print_r($dataResult);
echo json_encode($dataResult);
exit(0);