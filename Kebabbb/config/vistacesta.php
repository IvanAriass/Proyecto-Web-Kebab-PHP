<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require './database.php';

session_start();

$db = new Database();
$con = $db->conectar();

if(isset($_POST['borrar_linea'])) {
    
    $id_linea = $_POST['id_linea'];
    $id_pedidos = $_POST['id_pedidos'];
    $id_usuario = $_SESSION['id'];
        
    $sql = $con->prepare("DELETE FROM lineapedidos WHERE id=$id_linea");
    $sql->execute();
    
    // Contar lineas del pedido
    $sql = $con->prepare("SELECT COUNT(*) as total FROM lineapedidos l, pedidos p WHERE id_pedidos=$id_pedidos and p.estado = 'Carrito' and p.id_usuario = $id_usuario");
    $sql->execute();
    
    $resultado = $sql->fetchAll(PDO:: FETCH_ASSOC);
    
    foreach ($resultado as $row) {
        $total = $row['total'];
    }
    
    // Precio Total
    $sql = $con->prepare("SELECT SUM(precioLinea) as total FROM lineapedidos l, pedidos p WHERE l.id_pedidos = $id_pedidos and p.estado = 'Carrito' and p.id_usuario = $id_usuario"); 
    $sql->execute(); 
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC); 
    
    foreach($resultado as $row) { $subtotal = $row['total']; } 
    
    // Condición para calcular el precio total
    if ($total > 0) {
        $sql = "UPDATE pedidos SET precioTotal = $subtotal WHERE id = $id_pedidos";
        $con->query($sql);
    } else {
        $sql = $con->prepare("DELETE FROM pedidos WHERE id=$id_pedidos");
        $sql->execute();
    }

        
    header("Location:../web.php");
    
} elseif(isset($_POST['cancelar_pedido'])) {
    
    $id_pedidos = $_POST['id_pedidos'];
    
    $sql = $con->prepare("DELETE  FROM lineapedidos WHERE id_pedidos = $id_pedidos");
    $sql->execute();
    
    $sql = $con->prepare("DELETE FROM pedidos WHERE id = $id_pedidos");
    $sql->execute();
    
    header("Location:../web.php");
    
} elseif(isset($_POST['realizar_pedido'])) {
    
    $id_pedidos = $_POST['id_pedidos'];
    
    $sql = $con->prepare("UPDATE pedidos SET estado = 'Realizado' WHERE id = $id_pedidos");
    $sql->execute();
    
    header("Location:../web.php");

} else {
    

}