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
$getLocationAndSpecialty=new MedicalDirectory($db);
$resultLocation=$getLocationAndSpecialty->getLocation();
$locationStorage=[];
while($row=$resultLocation["code"]->fetch(PDO::FETCH_ASSOC)){
	$locationStorage[]=array("idlocation"=>$row["id_localidades"],"namelocation"=>utf8_encode($row["nombre_localidad"]));
}

$resultSpecialty=$getLocationAndSpecialty->getSpecialty();
$specialtyStorage=[];
while($row1=$resultSpecialty["code"]->fetch(PDO::FETCH_ASSOC)){
	$specialtyStorage[]=array("idSpecialty"=>$row1["id_especialidad"],"nameSpecialty"=>utf8_encode($row1["nombre_especialidad"]));
}

$dataResult=array("msg"=>$resultSpecialty["msg"],"locationStorage"=>$locationStorage,"specialtyStorage"=>$specialtyStorage);
echo json_encode($dataResult);
exit(0);