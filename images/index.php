<?php
/**
 * Created by PhpStorm.
 * User: julio.ramos
 * Date: 09/08/2018
 * Time: 01:14 PM
 */

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
	return $nombredia." | ".$nombreMes." ".$numeroDia." | ".$anio;
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
	$string = str_replace(array("\\", "¨", "º", "-", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/","(", ")", "?", "'", "¡","¿", "[", "^", "<code>", "]","+", "}", "{", "¨", "´",">", "< ", ";", ",", ":",".", " "),'',$string);
    return $string;
}

function cleanHtmlAndTags($textHtml){
	$cleanText=strip_tags($textHtml);
	$cleanText=html_entity_decode($cleanText);

return $cleanText;
}



$dateAndTime="2017-01-11 12:26:54";
list($currentDate,$currentTime)=explode(" ",$dateAndTime);
$dateToText=fechaCastellano($currentDate);

$sinAcentos=cleanText("HÓLÁ");

$noTagsHtml=cleanHtmlAndTags("<b>HÓLÁ</b>");
//echo $dateToText;
echo("<br>");
echo($noTagsHtml);
echo("<br>");

function extraerURLs($cadena){
	$regex = '/https?\:\/\/[^\"]+/i';
	preg_match_all($regex, $cadena, $partes);
	return ($partes[0][0]);
}

// Llamamos a la función y le pasamos la cadena a buscar
$cadena='<a href="http://www.servicioshuman.mx/tarjetasmb/informacion-SIX FLAGS-174" target="_blank"><p>Descarga tu cupón <strong>aquí</strong></p></a>';

$urls = extraerURLs($cadena);

print_r($urls);

// Listamos los resultados
//foreach($urls as $url){
//	echo $url.'<br />';
//}