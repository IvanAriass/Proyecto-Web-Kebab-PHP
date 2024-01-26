<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php 

session_start();

if ($_SESSION['id'] == null) {
    
    header("Location:./login.php");
}

if (isset($_POST['linea'])) {
    require './config/database.php';

    $db = new Database();
    $con = $db->conectar();
    
    $id_usuario = $_SESSION['id'];
    
    $id_pedido = $_POST['id_pedido']; $estado = $_POST['estado']; $total = $_POST['total']; $fecha = $_POST['fecha'];
    
    $sql = $con->prepare("SELECT DISTINCT l.id as id_linea, l.cantidad as cantidad, c.nombre as categoria, p.nombre, p.descripcion, l.precioLinea, l.id_pedidos as id_pedidos, f.estado as estado FROM lineapedidos l, productos p, categorias c, productoscategorias g, pedidos f where l.id_pedidos = $id_pedido and l.id_productos = p.id and c.id = g.id_categorias and p.id = g.id_productos and f.estado = 'Realizado' ");
    $sql->execute();
    $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

}

?>

<html>
    <head>
        <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp8IZri7lNVbmMGt1tRY0XX46IyFFbCCzAQTj7xbgggzY537D61mvSbPmDMS3VNWz-aBM&usqp=CAU">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
        <meta charset="UTF-8">
        <title>Linea del pedido</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                <style>
            * {    
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                text-decoration: none;
            }
            
            body {
                background-image: url('https://i.pinimg.com/originals/f5/f3/56/f5f356d842b829d0845872369b068fe6.jpg');
            }
            
            body, html {height: 100%}
            body,h1,h2,h3,h4,h5,h6 {font-family: "Amatic SC", sans-serif}
            .menu {display: none}
            .bgimg {
              background-repeat: no-repeat;
              background-size: cover;
              min-height: 95%;
            }
            
            .w3-button-form {
                width: 33%;
                margin-bottom: 10px;
            }
            
            hr {
                border-top: 1px solid #c9c9c9;
            }
            
            input, select {
                margin-top: 18px;
                width: 50%;
                height: 50%;
            }
            
            h3 {
                margin-right: 15px;
            }

        </style>
    </head>
    <body>
       
        <div class="w3-modal-content" style="margin-top: 10vh; width: 600px">
            <div class="w3-card-4">
                <header class="w3-container w3-black">
                    <a href="./web.php" style="margin-top: 0px;margin-bottom: 0px;"
                  class="w3-button w3-display-topright">Volver</a>
                  <h1>Linea del pedido: <?php echo $fecha ?></h1>
                </header>

                <div class="w3-container w3-white">
                  <?php foreach($productos as $row) { ?>
                            <?php if ($row['estado'] == "Realizado") { ?>
                            <div method="POST" action="./config/vistacesta.php">
                                <div>
                                  <h3><b><?php echo $row['categoria']; echo " "; echo $row['nombre']; ?></b>
                                     <span class='w3-right w3-tag w3-black w3-round'><?php echo $row['precioLinea']; ?>€</span>
                                     <span class='w3-right w3-tag w3-dark-gray w3-round'><?php echo "Cant: "; echo $row['cantidad']; ?></span></h3>
                                </div>
                                  <p class='w3-text-grey'><?php echo $row['descripcion']; ?></p>
                            </div>
                            <hr>
                        <?php } } ?>
                            <h3 class="w3-right">Subtotal:  <?php $sql = $con->prepare("SELECT SUM(precioLinea) as total FROM lineapedidos l, pedidos p WHERE l.id_pedidos = $id_pedido and p.id_usuario = $id_usuario and p.id = $id_pedido"); $sql->execute(); $resultado = $sql->fetchAll(PDO::FETCH_ASSOC); foreach($resultado as $row) { $subtotal = $row['total']; } echo $subtotal; ?> €</h3>
                </div>

                <footer class="w3-container w3-black">
                    
                </footer>
         </div>
        </div>
    </body>
</html>