<?php

$host="localhost:3307";
$user="root";
$pass="";
$db="tienda";

$conexion=new mysqli($host,$user,$pass,$db);

if(!$conexion){
    echo "Conexión fallida";
}
