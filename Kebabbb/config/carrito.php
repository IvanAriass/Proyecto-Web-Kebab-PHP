<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $fecha = date("Y-m-d H:i:s"); // Fecha actual
    $id_productos = $_POST['id']; // ID del producto enviado por el usuario
    $precioUnitario = $_POST['precioUnitario']; // Precio del producto enviado por el usuario
    $id_usuario = $_SESSION['id']; // ID del usuario
    $id_productosL = 0; // Definimos la variable para que no salte un warning
    
    // Consulta total de pedidos
    $sql = "SELECT COUNT(*) as total FROM pedidos WHERE id_usuario=$id_usuario && estado='Carrito'";

    $pedidos = $con->prepare($sql);
    $pedidos->execute();

    $resultado = $pedidos->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resultado as $row) {
            $total = $row['total']; // Conseguimos los pedidos totales
    }
    
    // Consulta id pedido
    $sql = "SELECT * FROM pedidos WHERE id_usuario=$id_usuario";

    $pedidos = $con->prepare($sql);
    $pedidos->execute();

    $resultado = $pedidos->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resultado as $row) {
            $id_pedido = $row['id']; // Conseguimos la id del pedido
    }    
    
    // CANCELADO, SOLO SE PUEDE ACCEDER LOGUEANDOSE
    if (!isset($_SESSION['nombre'])) {
        
        // Hacer un formulario para clientes que no han iniciado sesión

        // Insertar pedido en caso de no tener sesión
        $sql = "INSERT INTO pedidos(nombre, apellidos, dni, dirección, precioTotal, fecha, estado, id_usuario) VALUES ('$nombre','$apellidos ', '$dni', '$direccion', 0, '$fecha', 'Carrito', null)"; 
        $con->query($sql);

    } else {

        // Parametros del usuario
        $id_usuario = $_SESSION['id'];
        $nombre = $_SESSION['nombre'];
        $apellidos = $_SESSION['apellidos'];
        $dni = $_SESSION['dni'];
        $direccion = $_SESSION['direccion'];
        

        // Comprobamos si existen tablas en pedidos
        if ($total > 0) {
            
            // Obtenemos todas las tablas de pedidos
            $sql = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario";
            $pedidos = $con->prepare($sql);
            $pedidos->execute();
            
            $resultado = $pedidos->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtenemos todas las tablas de linea pedidos
            $sql = "SELECT * FROM lineapedidos WHERE id_pedidos=$id_pedido";
            $linea = $con->prepare($sql);
            $linea->execute();
            
            $lineaspedidos = $linea->fetchAll(PDO::FETCH_ASSOC);
            
            
            // Recorremos las todas las tablas de pedidos
            foreach ($resultado as $row) {
                
                // Si tiene el estado "Carrito" y el usuario es el mismo
                if ($row['estado'] == "Carrito" && $row['id_usuario'] == $id_usuario) {
                    
                    //Leemos las lineas del pedido para obtener la id del producto
                    foreach ($lineaspedidos as $linea) {
                        
                        if ($linea['id_productos'] == $id_productos) {
                            
                            $id_productosL = $linea['id_productos'];
                            $idL = $linea['id'];
                            $cantidad = $linea['cantidad'];
                            
                        }   
                    }
                    
                    if ($id_productos == $id_productosL) {
                        
                        $id_pedidos = $row['id'];
                        $cantidad = $cantidad +1;
                        $sql = "UPDATE lineapedidos SET cantidad = $cantidad, precioLinea=$precioUnitario*$cantidad WHERE id_productos = $id_productos AND id = $idL";
                        $con->query($sql);
                        
                        
                        // Actualizar precio linea
                        $sql = "UPDATE lineapedidos SET precioLinea = $precio WHERE id_productos = $id_productos AND id = $idL";
                        
                        // Precio Total
                        $sql = $con->prepare("SELECT SUM(precioLinea) as total FROM lineapedidos l, pedidos p WHERE l.id_pedidos = $id_pedidos and p.estado = 'Carrito' and p.id_usuario = $id_usuario"); 
                        $sql->execute(); 
                        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC); 
                        foreach($resultado as $row) { $subtotal = $row['total']; } 
                        
                        $sql = "UPDATE pedidos SET precioTotal = $subtotal WHERE id = $id_pedidos";
                        $con->query($sql);
                        
                        
                        header("Location:web.php#menu");
                        
                    } else {
                        
                        $id_pedidos = $row['id'];
                        $sql = "INSERT INTO lineapedidos(cantidad, precioLinea, id_pedidos, id_productos) VALUES (1, $precioUnitario, $id_pedido, $id_productos)";
                        $con->query($sql);
                        
                        // Precio Total
                        $sql = $con->prepare("SELECT SUM(precioLinea) as total FROM lineapedidos l, pedidos p WHERE l.id_pedidos = $id_pedidos and p.estado = 'Carrito' and p.id_usuario = $id_usuario"); 
                        $sql->execute(); 
                        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC); 
                        foreach($resultado as $row) { $subtotal = $row['total']; } 
                        
                        $sql = "UPDATE pedidos SET precioTotal = $subtotal WHERE id = $id_pedidos";
                        $con->query($sql);
                        
                        header("Location:web.php#menu");
                        

                    }
                                        
                // Si no tiene el estado "Carrito" y el usuario no es el mismo
                } 

            }
                
            
        } else {
            
             // Insertar pedido en caso de no tener ningún pedido con dicho usuario
            $sql = "INSERT INTO pedidos(nombre, apellidos, dni, dirección, precioTotal, fecha, estado, id_usuario) VALUES ('$nombre',' $apellidos', '$dni', '$direccion', $precioUnitario, '$fecha', 'Carrito', $id_usuario)";
            $con->query($sql);
 
            // Seleccionamos el último id insertado en pedidos para crear lineas para los pedidos
            $sql = ("SELECT LAST_INSERT_ID() as id");
            $resultado = $con->prepare($sql);
            $resultado->execute();
            
            foreach ($resultado as $row) {
                $id_pedidos = $row['id']; // Obtemos la id del pedido
            }

            $sql = "INSERT INTO lineapedidos(cantidad, precioLinea, id_pedidos, id_productos) VALUES (1, $precioUnitario, $id_pedidos, $id_productos)";
            $con->query($sql);
            
            header("Location:web.php#menu");

        }
    }  
}
