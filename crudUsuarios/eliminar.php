<?php

require '../config/conexion.php';

//Se obtiene el id del registro a eliminar
$id = $_GET['id'];

//Verificar si se recibio el id
if (isset($id)){
    
    $sql="Delete from usuarios where id=$id";

    if($conexion->query($sql) === TRUE){
        header("Location: ../usuarios.php");
    }else{
        echo ("Location: ../usuarios.php");
    }
}else{
    echo ("Location: ../usuarios.php");
}

