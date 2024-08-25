<?php

require '../config/conexion.php';

//Se obtiene el id del registro a eliminar
$id = $_GET['id'];

//Verificar si se recibio el id
if (isset($id)){
    
    $sql="Delete from compras where id=$id";

    if($conexion->query($sql) === TRUE){
        header("Location: ../compras.php");
    }else{
        echo ("Location: ../compras.php");
    }
}else{
    echo ("Location: ../compras.php");
}

