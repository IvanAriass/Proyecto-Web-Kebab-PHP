<?php

session_start();

if ($_SESSION['id'] == null) {
    
    header("Location:./login.php");
}

require './config/database.php';

$db = new Database();
$con = $db->conectar();

require './config/carrito.php';

$id_usuario = $_SESSION['id'];

// Consulta para mostrar los productos
$sql = $con->prepare("SELECT p.id, p.nombre, p.descripcion, p.precioUnitario, c.nombre as categoria, g.id_categorias FROM productos p, categorias c, productoscategorias g WHERE p.id = g.id_productos AND c.id = g.id_categorias;");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

// Consulta para mostrar las categorias
$sql = $con->prepare("SELECT * FROM categorias");
$sql->execute();
$categorias = $sql->fetchAll(PDO::FETCH_ASSOC);

// Consulta para conseguir la id del pedido dependiendo del usuario introducido
$sql = $con->prepare("SELECT * FROM db_pedidos.pedidos where id_usuario = $id_usuario and estado = 'Carrito';");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $row) {
    $id_pedidos = $row['id'];
}

// Consulta para mostrar los productos que están en el carrito
if (isset($id_pedidos)) {
    $sql = $con->prepare("SELECT DISTINCT l.id as id_linea, l.cantidad as cantidad, c.nombre as categoria, p.nombre, p.descripcion, l.precioLinea, l.id_pedidos as id_pedidos, f.estado as estado FROM lineapedidos l, productos p, categorias c, productoscategorias g, pedidos f where l.id_pedidos = $id_pedidos and l.id_productos = p.id and c.id = g.id_categorias and p.id = g.id_productos and f.estado = 'Carrito' ");
    $sql->execute();
    $carrito = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta para conseguir el id de la primera categoria
$sql=$con->prepare("SELECT id FROM categorias order by id limit 1;");
$sql->execute();

$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $row) { $id_primera_categoria = $row['id']; }

// Consulta para ver si existen pedidos
$sql=$con->prepare("select count(*) as total from pedidos where id_usuario = $id_usuario");
$sql->execute();
$existen = $sql->fetchAll(PDO:: FETCH_ASSOC);

foreach ($existen as $row) { $pedidosN = $row['total']; }
?>

<!DOCTYPE html>
<html>
<head>
<title>Euro Supa Kebab</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp8IZri7lNVbmMGt1tRY0XX46IyFFbCCzAQTj7xbgggzY537D61mvSbPmDMS3VNWz-aBM&usqp=CAU">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body, html {height: 100%}
body,h1,h2,h3,h4,h5,h6 {font-family: "Amatic SC", sans-serif}
.menu {display: none}
.bgimg {
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url("https://images8.alphacoders.com/745/thumb-1920-745042.jpg");
  min-height: 95%;
}
</style>
</head>
<body>

<!-- Navbar (sit on top) -->
<div class="w3-top w3-hide-small">
  <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
      <div class="w3-cell-row">
        <div class="w3-container w3-cell w3-left">
          <a href="#" class="w3-bar-item w3-button">HOME</a>
          <a href="#menu" class="w3-bar-item w3-button">MENU</a>
          <a href="#about" class="w3-bar-item w3-button">MI TIENDA</a>
        </div>
        <div class="w3-container w3-cell w3-right" style="display: flex; justify-content: center">
            <a href="./login.php" class="w3-bar-item w3-button"><i class="material-icons">&#xe80e;</i> LOGIN / CERRAR SESIÓN </a>
            <button onclick="document.getElementById('id02').style.display='block'" class="w3-button w3-bar-item"><i class="fa-solid fa-user" style="font-size:18px;"></i> PERFIL</button>
            <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-bar-item"><i style="font-size:21px;" class="fa">&#xf09d;</i> CESTA</button>

            
            <!<!-- MODAL 1  -->
            <div id="id01" class="w3-modal">
              <div class="w3-modal-content">
                <header class="w3-container w3-black"> 
                  <span onclick="document.getElementById('id01').style.display='none'" 
                  class="w3-button w3-display-topright">&times;</span>
                  <h1>Cesta</h1>
                </header>
     
                    <div class="w3-container" style="color: black;">
                        
                    <?php if(isset($id_pedidos)) { ?>
                    <?php foreach($carrito as $row) { ?>
                        <?php if ($row['estado'] == "Carrito") { ?>
                        <form method="POST" action="./config/vistacesta.php">
                            <div>
                              <h3><b><?php echo $row['categoria']; echo " "; echo $row['nombre']; ?></b>
                                 <button class="w3-button w3-red w3-right w3-tag w3-round" type="submit" name="borrar_linea" style="padding-top:0px; padding-bottom: 0px; margin-left: 2px; margin-right: 2px"><i class="fa-solid fa-trash" style="font-size:21px;"></i></button>
                                 <span class='w3-right w3-tag w3-black w3-round' style="margin-left: 2px; margin-right: 2px"><?php echo $row['precioLinea']; ?>€</span>
                                 <span class='w3-right w3-tag w3-dark-gray w3-round' style="margin-left: 2px; margin-right: 2px"><?php echo "Cant: "; echo $row['cantidad']; ?></span></h3>
                            </div>
                              <p class='w3-text-grey'><?php echo $row['descripcion']; ?></p>
                              <?php $id_pedidos = $row['id_pedidos']; ?>
                              <input type="hidden" name="id_linea" value="<?php echo $row['id_linea'] ?>">
                              <input type="hidden" name="id_pedidos" value="<?php echo $row['id_pedidos'] ?>">
                        </form>
                        <hr>
                    <?php } } } else { ?>
                        <div>
                            <h1 class="w3-center">No existen pedidos</h1>
                        </div>
                    <?php } ?>
                        <span class="w3-right w3-text-black">Subtotal: <?php if(isset($id_pedidos)) { $sql = $con->prepare("SELECT SUM(precioLinea) as total FROM lineapedidos l, pedidos p WHERE l.id_pedidos = $id_pedidos and p.estado = 'Carrito' and p.id_usuario = $id_usuario"); $sql->execute(); $resultado = $sql->fetchAll(PDO::FETCH_ASSOC); foreach($resultado as $row) { $subtotal = $row['total']; } echo $subtotal; }?></span>
                    </div>
                  <?php if(isset($id_pedidos)) { ?>
                  <form class="w3-container w3-black" style="padding-right: 0px;" method="POST" action="./config/vistacesta.php">
                    <div style="display: flex; justify-content: right;">
                        <button class="w3-button w3-dark-gray" type="submit" name="realizar_pedido">Comprar</button>
                        <button class="w3-button w3-red" type="submit" name="cancelar_pedido">Cancelar Pedido</button>
                        <input type="hidden" name="id_pedidos" value="<?php echo $id_pedidos ?>">
                    </div>
                </form>
                  <?php } ?>

              </div>
            </div>
            
            <!<!-- MODAL 2  -->
            
            <div id="id02" class="w3-modal">
              <div class="w3-modal-content">
                <header class="w3-container w3-black"> 
                  <span onclick="document.getElementById('id02').style.display='none'" 
                  class="w3-button w3-display-topright">&times;</span>
                  <h1>Perfil</h1>
                </header>
     
                    <div class="w3-container" style="color: black; display: flex; ">
                        <div style="width: 40%;" >
                            <h1 style="margin-bottom: 0px">Datos personales</h1>
                            <hr style="border: 2px solid red; margin-right: 80px; margin-top: 0px">
                            <h3><b>Nombre: </b><?php echo $_SESSION['nombre']; ?></h3>
                            <h3><b>Apellidos: </b><?php echo $_SESSION['apellidos']; ?></h3>
                            <h3><b>Email: </b><?php echo $_SESSION['email']; ?></h3>
                            <h3><b>Dirección: </b><?php echo $_SESSION['direccion']; ?></h3>
                            <h3><b>Telefono: </b><?php echo $_SESSION['telefono']; ?></h3>
                        </div>
                        <div style="width: 60%;">
                            <h1>Pedidos</h1>
                            <div style="color: black; display: flex; justify-content: center; flex-wrap: wrap;">
                                <?php
                            // Consulta para mostrar las categorias
                            $sql = $con->prepare("SELECT * FROM pedidos WHERE id_usuario=$id_usuario and estado='Realizado'");
                            $sql->execute();
                            $pedidos = $sql->fetchAll(PDO::FETCH_ASSOC);
                            
                            if ($pedidosN != 0) {
                            ?>
                           <?php foreach($pedidos as $row) { ?>
                            <?php if (isset($row['id'])) { ?>
                            <?php $dni = $row['dni']; $fecha = $row['fecha']; $estado = $row['estado']; $total = $row['precioTotal'] ?>
                            <div style="display:flex; width: 100%;">
                                <div style="width: 100%; display: flex; flex-direction: row; justify-content: row">
                                    <div style="width:5%">
                                            <span class='w3-tag w3-red w3-round' style="font-size: 24px;"><?php echo $row['id']; ?></span>
                                    </div>
                                    <form style="width:97.5%" action="lineapedido.php" method="POST">
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 35%;"><?php echo "<b>Fecha: </b>$fecha"; ?></span>
                                        <span class="w3-tag w3-white" style="font-size: 24px; width: 35%;"><?php echo "<b>Precio Total: </b>$total"; ?> €</span>
                                        
                                        <input type="hidden" value="<?php echo $estado ?>" name="estado">
                                        <input type="hidden" value="<?php echo $total ?>" name="total">
                                        <input type="hidden" value="<?php echo $fecha ?>" name="fecha">
                                        <input type="hidden" value="<?php echo $row['id'] ?>" name="id_pedido">
                                        <button class="w3-button w3-dark-gray w3-right" style="padding: 0px; padding-right: 7px; padding-left: 7px" id="mostrarLinea" action="submit" name="linea">Ir a la linea</button>
                                    </form>
                                    <hr>
                                </div>
                                <?php } ?>
                            </div>                            
                           
                            <?php } } else { ?>
                                <div>
                                    <h1 class="w3-center">No existen pedidos</h1>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                  <footer class="w3-container w3-black" style="padding-right: 0px;">
                      <br>
                  </footer>
              </div>
            </div>
            <?php 
                if (isset($_SESSION['rol']) && $_SESSION['rol'] == "Admin") { 
                    echo "<a href='./ZonaAdmin.php' class='w3-bar-item w3-button visibilidad'><i class='fa-solid fa-lock' style='font-size:21px;'></i> ZONA ADMIN</a>"; 

                }
            ?>
        </div>
      </div>
  </div>
</div>
<!-- Header with image -->
<header class="bgimg w3-display-container w3-grayscale-min" id="home">
  <div class="w3-display-bottomleft w3-padding">
    <span class="w3-tag w3-xlarge">Abierto de 10am a 12pm</span>
  </div>
  <div class="w3-display-middle w3-center">
    <span class="w3-text-white w3-hide-small" style="font-size:100px"><b>euro<br>SUPA KEBAB</b></span>
    <span class="w3-text-white w3-hide-large w3-hide-medium" style="font-size:60px"><b>euro<br>SUPA KEBAB</b></span>
    <p><a href="#menu" class="w3-button w3-xxlarge w3-black">Ver el menu</a></p>
  </div>
</header>

<!-- Menu Container -->
<div class="w3-container w3-black w3-padding-64 w3-xxlarge" id="menu">
  <div class="w3-content">
  
    <h1 class="w3-center w3-jumbo" style="margin-bottom:64px">ER MENU</h1>
    <div class="w3-row w3-center w3-border w3-border-dark-grey">
    <?php foreach ($categorias as $row) { ?>
      <?php if ($row['id'] == $id_primera_categoria) { ?>
      <a href="javascript:void(0)" onclick="openMenu(event, '<?php echo $row['nombre'] ?>');" id="myLink">
        <div class="w3-col s4 tablink w3-padding-large w3-hover-red"><?php echo $row['nombre'] ?></div>
      </a>
      <?php } else { ?>
      <a href="javascript:void(0)" onclick="openMenu(event, '<?php echo $row['nombre'] ?>');">
        <div class="w3-col s4 tablink w3-padding-large w3-hover-red"><?php echo $row['nombre'] ?></div>
      </a>
      <?php } } ?>
    </div>

     <?php foreach($categorias as $row1) { ?>
    
    <div id='<?php echo $row1['nombre'] ?>' class='w3-container menu w3-padding-32 w3-white'>
        <?php foreach($productos as $row) { ?>
        <?php if ($row['id_categorias'] == $row1['id']) { ?>
        <form method="POST" action="<?php $_SERVER["PHP_SELF"];?>">
            <h1><b><?php echo $row['categoria']; echo " "; echo $row['nombre']; ?></b><span class='w3-right w3-tag w3-dark-grey w3-round'><?php echo $row['precioUnitario']; ?>€</span></h1>
            <p class='w3-text-grey'><?php echo $row['descripcion']; ?></p>
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
            <input type="hidden" name="precioUnitario" value="<?php echo $row['precioUnitario'] ?>">
            <div style="display: flex; justify-content: right;">
                <button class="w3-button w3-gray" type="submit">Añadir a la cesta</button>
            </div>
        </form>
        <hr>
        <?php } ?>
      <?php } ?>
    </div>
     <?php } ?>
        
<br>
  </div>
</div>

<!-- About Container -->
<div class="w3-container w3-padding-64 w3-red w3-grayscale w3-xlarge" id="about">
  <div class="w3-content">
    <h1 class="w3-center w3-jumbo" style="margin-bottom:64px">Mi tienda</h1>
    <div class="w3-card-3">
        <div class="w3-cell-row">
            <div class="w3-container w3-cell w3-left">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYObOHpeDAZ9KlN8plkNN5sBLbKbqbTIX6XWsgwSpcrUF1yWFF6ftTa4pJE8vqRt1W2o4&usqp=CAU" alt="Avatar" class="w3-left w3-circle">
            </div>
            <div class="w3-container w3-cell">
                <p>¡Hola! <strong> Somos Rahmed y Ismael </strong>esta es nuestra tienda. Tenemos kebabs de rollo, box, pita y muchas más, pero en la tienda digital solo servimos los que ya he mencionado anteriormente.</p>
            </div>
        </div>
    </div>
    <p>Tamo orgullosos interiores</p>
    
    <div class="w3-row">
        <div class="w3-container w3-cell w3-center" style="width: 50%;">
            <img src="https://i.pinimg.com/originals/66/1a/8d/661a8d33118661fe68c3c7909832ecf4.jpg" style="width:100%" class="w3-margin-top w3-margin-bottom" alt="Restaurant">
        </div>
        <div class="w3-container w3-cell" style="width: 50%;">
            <h1><b>Horario</b></h1>
    
            <div class="w3-row">
                <div class="w3-col s6">
                    <p>Lun & Mar CERRAO</p>
                    <p>Miércoles 10.00 - 24.00</p>
                    <p>Jueves 10:00 - 24:00</p>
                </div>
                <div class="w3-col s6">
                    <p>Viernes 10:00 - 12:00</p>
                    <p>Sábado 10:00 - 23:00</p>
                    <p>Domingo CERRAO</p>
                </div>
            </div>
        </div>
    </div>  
  </div>
</div>

<!-- Footer -->
<footer class="w3-center w3-black w3-padding-48 w3-xxlarge">
  <p><a href="#" class="w3-hover-text-green">Ir parriba</a></p>
</footer>

<script>
// Tabbed Menu
function openMenu(evt, menuName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("menu");
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
     tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
  }
  document.getElementById(menuName).style.display = "block";
  evt.currentTarget.firstElementChild.className += " w3-red";
}
document.getElementById("myLink").click();


//Cambiar cantidad
function llamarFuncionJS() {
            $.ajax({
                type: "POST",
                url: "./config/modificar_cant.php",
                data: {},
                success: function(response) {
                    // La función JavaScript se ejecutará una vez que se reciba la respuesta.
                    document.write(response);
                }
            });
        }
        
function miFuncionJavaScript() {
  alert("¡Esta es una función JavaScript!");
}


</script>

</body>
</html>