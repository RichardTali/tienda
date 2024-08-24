<?php

require '../config/conexion.php';

//Se obtiene el id del registro a eliminar
$id = $_GET['id'];

//Verificar si se recibio el id
if (isset($id)){
    
    $sql="Delete from categorias where id=$id";

    if($conexion->query($sql) === TRUE){
        header("Location: ../categoria.php");
    }else{
        echo ("Location: ../categoria.php");
    }
}else{
    echo ("Location: ../categoria.php");
}

