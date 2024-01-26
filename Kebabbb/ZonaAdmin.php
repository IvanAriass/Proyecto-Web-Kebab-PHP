<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<?php
session_start();

if (session_status() == PHP_SESSION_NONE || $_SESSION['rol'] != "Admin") {
    
    header("Location:./login.php");
}

require './config/database.php';

$db = new Database();
$con = $db->conectar();

$errores = "false";

if (isset($_GET['errores'])) {
    $errores = $_GET['errores'];
    $error = $_GET['error'];
}


?>
<html>
    <head>
        <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp8IZri7lNVbmMGt1tRY0XX46IyFFbCCzAQTj7xbgggzY537D61mvSbPmDMS3VNWz-aBM&usqp=CAU">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
        <meta charset="UTF-8">
        <title>Zona Admin</title>
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
        <div class="w3-top w3-hide-small" style="">
            <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
                <div class="w3-cell-row">
                  <div class="w3-container w3-cell w3-left">
                    <a href="./web.php#" class="w3-bar-item w3-button">HOME</a>
                    <a href="./web.php#menu" class="w3-bar-item w3-button">MENU</a>
                    <a href="./web.php#about" class="w3-bar-item w3-button">MI TIENDA</a>
                  </div>

                  <div class="w3-container w3-cell w3-right">
                    <a href="./login.php" class="w3-bar-item w3-button"><i class="material-icons">&#xe80e;</i> LOGIN / CERRAR SESIÓN </a>
                    <a href="./ZonaAdmin.php" class="w3-bar-item w3-button"><i class='fa-solid fa-lock' style='font-size:21px;'></i> ZONA ADMIN </a>
                  </div>
                  
                </div>
            </div>
        </div>
                  
        <main style="display:flex; justify-content: center; padding-top: 150px">
            <div class="w3-container w3-card-4 w3-white" style="width: 60%; border-radius: 20px">
                <h1 class="w3-center"><b>Zona Admin</b></h1>
                <hr>

                <h3 class="w3-center"><b>Productos</b></h3>
                
                <div class="w3-center" style="padding-bottom: 20px; display: flex; flex-direction: row; justify-content: space-between; flex-wrap: wrap;">
                    <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple"> Añadir producto al formulario </button>
                    <button onclick="document.getElementById('id02').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="ModificarProducto"> Modificar producto del formulario </button>
                    <button onclick="document.getElementById('id03').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="BorrarProducto"> Borrar producto del formulario </button>
                    <button onclick="document.getElementById('id04').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="VerProductos"> Ver Productos </button>
                </div>
                
                <!-- Añadir Productos -->
                <div id="id01" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id01').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Añadir Producto</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">

                                    <div style="display: flex; justify-content: space-between; width: 60%;">
                                        <h3><b>Nombre: </b></h3>
                                      <input type="text" name="nombre" required>
                                    </div>
                                    <div style="display: flex;justify-content: space-between; width: 60%; ">
                                        <h3><b>Descripción: </b></h3>
                                      <input type="text" name="descripcion" required>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>Precio Unitario: </b></h3>
                                      <input type="number" name="precioUnitario" min="0" step="0.1" required>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>Categoria: </b></h3>
                                    <select type="select" name="categorias">
                                          <?php
                                               $sql = $con->prepare("SELECT * FROM CATEGORIAS");
                                               $sql->execute();
                                               $listacategorias = $sql->fetchAll(PDO::FETCH_ASSOC);
                                               
                                               foreach ($listacategorias as $row) {
                                                   $nombre = $row['nombre'];
                                                   $id_categoria = $row['id'];
                                                   echo "<option value='$id_categoria '>$nombre</option>";
                                               }
                                          ?>
                                    </select>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="AñadirProducto">Añadir</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
                <!-- Modificar Productos -->
                <div id="id02" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id02').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Modificar Producto</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>ID: </b></h3>
                                        <select type="select" name="id">
                                              <?php
                                                   $sql = $con->prepare("SELECT p.id, p.nombre, p.descripcion, p.precioUnitario, c.nombre as categoria, g.id_categorias FROM productos p, categorias c, productoscategorias g WHERE p.id = g.id_productos AND c.id = g.id_categorias;");
                                                   $sql->execute();
                                                   $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                   foreach ($productos as $row) {
                                                       $nombre = $row['nombre'];
                                                       $categoria = $row['categoria'];
                                                       $id_productos = $row['id'];
                                                       echo "<option value='$id_productos '>$id_productos $categoria $nombre</option>";
                                                   }
                                              ?>
                                        </select>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 60%;">
                                        <h3><b>Nombre: </b></h3>
                                        <input type="text" name="nombre" required>
                                    </div>
                                    <div style="display: flex;justify-content: space-between; width: 60%; ">
                                        <h3><b>Descripción: </b></h3>
                                      <input type="text" name="descripcion" required>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>Precio Unitario: </b></h3>
                                      <input type="number" name="precioUnitario" required>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="ModificarProducto">Modificar</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
                <!-- Borrar Productos -->
                <div id="id03" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id03').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Borrar Producto</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>ID: </b></h3>
                                        <select type="select" name="id">
                                              <?php
                                                   $sql = $con->prepare("SELECT p.id, p.nombre, p.descripcion, p.precioUnitario, c.nombre as categoria, g.id_categorias FROM productos p, categorias c, productoscategorias g WHERE p.id = g.id_productos AND c.id = g.id_categorias;");
                                                   $sql->execute();
                                                   $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                   foreach ($productos as $row) {
                                                       $nombre = $row['nombre'];
                                                       $categoria = $row['categoria'];
                                                       $id_productos = $row['id'];
                                                       $id_categorias = $row['id_categorias'];
                                                       
                                                       echo "<option value='$id_productos '>$id_productos $categoria $nombre</option>";
                                                   }
                                              ?>
                                        </select>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="BorrarProducto">Borrar</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
               <!-- /Ver Productos -->
                <div id="id04" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id04').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Ver Productos</h1>
                      </header>
                        <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                            <?php
                            // Consulta para mostrar los productos
                            $sql = $con->prepare("SELECT p.id, p.nombre, p.descripcion, p.precioUnitario, c.nombre as categoria, g.id_categorias FROM productos p, categorias c, productoscategorias g WHERE p.id = g.id_productos AND c.id = g.id_categorias;");
                            $sql->execute();
                            $productos = $sql->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                           <?php foreach($productos as $row) { ?>
                            <?php if (isset($row['id_categorias'])) { ?>
                            <div style="display:flex; width: 100%;">
                                <div style="width: 100%;">
                                    <h1><b><span class='w3-tag w3-red w3-round' style="font-size: 24px;"><?php echo $row['id']; ?></span><span class="w3-tag w3-white"><?php echo $row['categoria']; echo " "; echo $row['nombre']; ?></span></b><span class='w3-right w3-tag w3-dark-grey w3-round'><?php echo $row['precioUnitario']; ?>€</span></h1>
                                    <p class='w3-text-grey'><?php echo $row['descripcion']; ?></p>
                                    <hr>
                                </div>
                            </div>
                            <?php } ?>
                          <?php } ?>
                                

                        </div>
                        <footer class="w3-container w3-black" style="padding-right: 0px;">
                        </footer>
                    </div>
                  </div>
                
                <h3 class="w3-center"><b>Categorias</b></h3>
                
                <div class="w3-center" style="padding-bottom: 20px; display: flex; flex-direction: row; justify-content: space-between; flex-wrap: wrap">
                    <button onclick="document.getElementById('id05').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="AñadirCategoria"> Añadir categoria al formulario </button>
                    <button onclick="document.getElementById('id06').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="ModificarCategoria"> Modificar categria del formulario </button>
                    <button onclick="document.getElementById('id07').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="BorrarCategoria"> Borrar categoria del formulario </button>
                    <button onclick="document.getElementById('id08').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="VerCategorias"> Ver categorias </button>
                </div>
                
                <!-- Añadir Categorias -->
                <div id="id05" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id05').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Añadir Categorias</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">

                                    <div style="display: flex; justify-content: space-between; width: 60%;">
                                        <h3><b>Nombre: </b></h3>
                                      <input type="text" name="nombre" required>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="AñadirCategoria">Añadir</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
                <!-- Modificar Productos -->
                <div id="id06" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id06').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Modificar Categoria</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>ID: </b></h3>
                                        <select type="select" name="id">
                                              <?php
                                                   $sql = $con->prepare("SELECT * FROM categorias");
                                                   $sql->execute();
                                                   $categorias = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                   foreach ($categorias as $row) {
                                                       $id = $row['id'];
                                                       $nombre = $row['nombre'];
                                                       echo "<option value='$id '>$id $nombre</option>";
                                                   }
                                              ?>
                                        </select>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 60%;">
                                        <h3><b>Nombre: </b></h3>
                                        <input type="text" name="nombre" required>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="ModificarCategoria">Modificar</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
                <!-- Borrar Categorias -->
                <div id="id07" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id07').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Borrar Categoria</h1>
                      </header>
                        <form method="POST" action="./config/modificaciones_admin.php">
                            <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                                    <div style="display: flex; justify-content: space-between; width: 60%; ">
                                        <h3><b>ID: </b></h3>
                                        <select type="select" name="id">
                                              <?php
                                                   $sql = $con->prepare("SELECT * FROM categorias");
                                                   $sql->execute();
                                                   $categorias = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                   foreach ($categorias as $row) {
                                                       
                                                       $id = $row['id'];
                                                       $nombre = $row['nombre'];
                                                       
                                                       echo "<option value='$id '>$id $nombre</option>";
                                                   }
                                              ?>
                                        </select>
                                    </div>

                            </div>
                            <footer class="w3-container w3-black" style="padding-right: 0px;">
                                <div style="display: flex; justify-content: right;">
                                    <button class="w3-button w3-dark-gray" type="submit" name="BorrarCategoria">Borrar</button>
                                </div>
                            </footer>
                        </form>
                    </div>
                  </div>
                
                <!-- /Ver Categorias -->
                <div id="id08" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id08').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Ver Categorias</h1>
                      </header>
                        <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                            <?php
                            // Consulta para mostrar las categorias
                            $sql = $con->prepare("SELECT * FROM categorias");
                            $sql->execute();
                            $categorias = $sql->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                           <?php foreach($categorias as $row) { ?>
                            <?php if (isset($row['id'])) { ?>
                            <div style="display:flex; width: 100%;">
                                <div style="width: 100%;">
                                    <h1><b><span class='w3-tag w3-red w3-round' style="font-size: 24px;"><?php echo $row['id']; ?></span><span class="w3-tag w3-white"><?php echo $row['nombre'];?></span></b></h1>
                                    <hr>
                                </div>
                            </div>
                            <?php } ?>
                          <?php } ?>
                                

                        </div>
                        <footer class="w3-container w3-black" style="padding-right: 0px;">
                        </footer>
                    </div>
                  </div>
                
                <h3 class="w3-center"><b>Ver Pedidos</b></h3>
                
                <div class="w3-center" style="padding-bottom: 20px; display: flex; flex-direction: row; justify-content: center;">
                    <button onclick="document.getElementById('id09').style.display='block'" class="w3-button w3-button-form w3-black w3-ripple" name="VerPedidos"> Ver Pedidos </button>
                </div>
                
                <!-- /Ver Categorias -->
                <div id="id09" class="w3-modal">
                    <div class="w3-modal-content">
                      <header class="w3-container w3-black"> 
                        <span onclick="document.getElementById('id09').style.display='none'" 
                        class="w3-button w3-display-topright">&times;</span>
                        <h1>Ver Pedidos</h1>
                      </header>
                        <div class="w3-container" style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                            <?php
                            // Consulta para mostrar las categorias
                            $sql = $con->prepare("SELECT * FROM pedidos");
                            $sql->execute();
                            $pedidos = $sql->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                           <?php foreach($pedidos as $row) { ?>
                            <?php if (isset($row['id'])) { ?>
                            <?php $dni = $row['dni']; $fecha = $row['fecha']; $estado = $row['estado']; $total = $row['precioTotal'] ?>
                            <div style="display:flex; width: 100%;">
                                <div style="width: 100%; display: flex; flex-direction: row; justify-content: row">
                                    <div style="width:2.5%">
                                            <span class='w3-tag w3-red w3-round' style="font-size: 24px;"><?php echo $row['id']; ?></span>
                                    </div>
                                    <div style="width:97.5%">
                                        <b><span class="w3-tag w3-white" style="font-size: 24px; width: 19%;"><?php echo $row['nombre']; ?></span></b>
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 19%;"><?php echo "<b>DNI: </b>$dni"; ?></span>
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 19%;"><?php echo "<b>Precio Total: </b>$total"; ?> €</span>
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 19%;"><?php echo "<b>Estado: </b>$estado"; ?></span>
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 19%;"><?php echo "<b>Fecha: </b>$fecha"; ?></span>
                                    </div>
                                        
                                    <hr>
                                </div>
                                <?php } ?>
                            </div>                            
                           
                          <?php } ?>
                                

                        </div>
                        <footer class="w3-container w3-black" style="padding-right: 0px;">
                        </footer>
                    </div>
                  </div>
            </div>
        </main>
        <?php if ($errores == "true") {
                
                echo "<div style='display: flex; justify-content: center'><h3 style=color:white;>Error: Ya existe una categoria con ese nombre</h3></div>";
                
             } 
            ?>
    </body>
</html>