<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require './database.php';

$db = new Database();
$con = $db->conectar();

if (isset($_POST['AñadirProducto'])) {
    
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precioUnitario'];
    $id_categorias = $_POST['categorias'];
    
    //Insertamos el nuevo producto
    $sql = $con->prepare("INSERT INTO productos(nombre,descripcion,precioUnitario) VALUES ('$nombre', '$descripcion', $precioUnitario)");
    $sql->execute();
    
    // Seleccionamos el último id insertado en pedidos para crear lineas para los pedidos
    $sql = ("SELECT LAST_INSERT_ID() as id");
    $resultado = $con->prepare($sql);
    $resultado->execute();

    foreach ($resultado as $row) {
        $id_productos = $row['id']; // Obtemos la id del pedido
    }
        
    $sql = $con->prepare("INSERT INTO productoscategorias(id_categorias, id_productos) VALUES ($id_categorias, $id_productos)");
    $sql->execute();
    
    header("Location: ../ZonaAdmin.php");
}

elseif (isset($_POST['ModificarProducto'])) {
    
    $id_productos = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precioUnitario'];
    
    //Modificamos el producto
    $sql = $con->prepare("UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precioUnitario = $precioUnitario WHERE id = $id_productos");
    $sql->execute();
    
    header("Location: ../ZonaAdmin.php");
    
}

elseif (isset($_POST['BorrarProducto'])) {
    
    $id_productos = $_POST['id'];
    
    $sql = $con->prepare("DELETE FROM productoscategorias WHERE id_productos=$id_productos");
    $sql->execute();
    
    $sql = $con->prepare("DELETE FROM productos WHERE id=$id_productos");
    $sql->execute();
    
    $sql = $con->prepare("DELETE FROM categorias WHERE id NOT IN (SELECT DISTINCT id_categorias FROM productoscategorias)");
    $sql->execute();
    
    header("Location: ../ZonaAdmin.php");
}

elseif (isset($_POST['AñadirCategoria'])) {
    
    $nombre = $_POST['nombre'];
    
    $sql = $con->prepare("SELECT * FROM categorias");
    $sql->execute();
    
    $categorias = $sql->fetchAll(PDO:: FETCH_ASSOC);
    
    foreach ($categorias as $row) {
        if ($row['nombre'] == $nombre) {
            
            $errores = "true";
            echo "gola";
            
            $redireccionURL = "http://localhost/Kebabbb/ZonaAdmin.php?errores=" . urlencode($errores);
            header("Location: " . $redireccionURL);
        }
    }
    
    if (!isset($errores)) {
        $sql = $con->prepare("INSERT INTO categorias(nombre) VALUES ('$nombre')");
        $sql->execute();

    header("Location: ../ZonaAdmin.php");

    }

    
}

elseif (isset($_POST['ModificarCategoria'])) {
    
    $id_categorias = $_POST['id'];
    $nombre = $_POST['nombre'];
    
    //Modificamos la categoria
    $sql = $con->prepare("UPDATE categorias SET nombre = '$nombre' WHERE id = $id_categorias");
    $sql->execute();
    
    header("Location: ../ZonaAdmin.php");
    
}

elseif (isset($_POST['BorrarCategoria'])) {
    
    $id_categorias = $_POST['id'];
            
    $sql = $con->prepare("DELETE FROM productoscategorias WHERE id_categorias = $id_categorias");
    $sql->execute();
    
    $sql = $con->prepare("DELETE FROM categorias WHERE id=$id_categorias");
    $sql->execute();
    
    
    header("Location: ../ZonaAdmin.php");
}
