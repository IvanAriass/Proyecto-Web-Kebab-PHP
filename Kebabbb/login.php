<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    
require './config/database.php';

$db = new Database();
$con = $db->conectar();

$errores = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST['login']))
        {
            if (isset($_POST["email"]) && isset($_POST["password"])) {
                
                $email= $_POST["email"]; $contraseña = $_POST["password"];

                try {
                    $sql = 'SELECT * FROM usuarios';
                    $usuarios = $con->query($sql); // Se realiza la query

                    foreach ($usuarios as $usuario) {
                        if ($usuario['email'] == $email && $usuario['contraseña'] == $contraseña) {
                            session_start();
                            $_SESSION["id"] = $usuario['id'];
                            $_SESSION["nombre"] = $usuario['nombre'];
                            $_SESSION["apellidos"] = $usuario['apellidos'];
                            $_SESSION["email"] = $usuario['email'];
                            $_SESSION["contraseña"] = $usuario['contraseña'];
                            $_SESSION["rol"] = $usuario['rol'];
                            $_SESSION["dni"] = $usuario['dni'];
                            $_SESSION["direccion"] = $usuario['dirección'];
                            $_SESSION["telefono"] = $usuario['telefono'];
                            header("Location:./web.php");
                        }
                    }
                } catch (Exception $ex) {
                    
                    echo "Error :" . $ex->getMessage();
                    
                }
            }
        } 
        
        else if (isset($_POST['registrarse'])) 
        {    
            try {
                
                $nombre = $_POST['name']; $apellidos = $_POST['surname']; $email = $_POST['email'];
                $contraseña = $_POST['password']; $rol = $_POST['rol']; $dni = $_POST['dni'];
                $direccion = $_POST['adress']; $telefono = $_POST['phone'];
                
                
                // Insertar a la base de datos
                $sql = "INSERT INTO usuarios(nombre, apellidos, email, contraseña, rol, dni, dirección, telefono) VALUES ('$nombre', '$apellidos', '$email', '$contraseña', '$rol', '$dni', '$direccion', '$telefono')";
                $con -> query($sql);
                header("Location:login.php");
                
            } catch (Exception $ex) {
                $error = $ex;
                $errores = true;
            }
        } 
        else 
        {
            echo 'ERROR: Se esperaba una solicitud POST';
        }
            
    }
    
?>

﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://fonts.googleapis.com/css?family=Amatic SC' rel='stylesheet'>
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp8IZri7lNVbmMGt1tRY0XX46IyFFbCCzAQTj7xbgggzY537D61mvSbPmDMS3VNWz-aBM&usqp=CAU">
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">  
</head>
<body>

    <main>

        <div class="contenedor__todo">
        <!--Caja Trasera global-->
            <div class="caja__trasera">
			<!--Caja Trasera(registro)-->
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
			<!--Caja Trasera(registro)-->
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes cuenta?</h3>
                    <p>Regístrate para iniciar sesión</p>
                    <button id="btn__registrarse">Regístrate</button>
                </div>
            </div>
            <!--Formulario de login y registro-->
            <div class="contenedor__login-register">
                <!--Login-->
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" class="formulario__login" method="POST">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" id="email" placeholder="Email" name="email" required>
                    <input type="password" id="password" name="password" placeholder="Contraseña" required>
                    <button type="submit" name="login">Entrar</button>
                </form>
                <!--Registro-->
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" class="formulario__register" method="POST">
                    <h2>Regístrarse</h2>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <input type="text" placeholder="Nombre" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="Apellidos" id="surname" name="surname" required>
                        </div>
                        <div class="col-md-12">
                            <input type="text" placeholder="Correo Electronico" id="email" name="email" required>
                        </div>
                        <div class="col-md-12">
                            <input type="password" placeholder="Contraseña" id="password" name="password" required>
                        </div>
                        <div class="col-md-12">
                            <select name="rol" required>
                                <option value="Admin" selected>Admin</option>
                                <option value="user">Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <input type="text" placeholder="DNI" id="dni" name="dni" minlength="9" maxlength="9" required>
                        </div>
                        <div class="col-md-12">
                            <input type="text" placeholder="Dirección" id="direccion" name="adress" required>
                        </div>
                        <div class="col-md-12">
                            <input type="num" placeholder="Teléfono" id="phone" name="phone" minlength="9" maxlength="9" required>
					<span id="ValidarFormlulario"></span>
                        </div>     
                    </div>                   
                    <button type="submit" name="registrarse">Registrarse</button>
                </form>
            </div>
        </div>
        
        <!-- Errores-->
        <?php if ($errores == true) { ?>
        <div id="id01" class="w3-modal">
            <div class="w3-modal-content">
              <header class="w3-container w3-red"> 
                <span onclick="document.getElementById('id01').style.display='none'" 
                class="w3-button w3-display-topright">&times;</span>
                <h1>ERROR</h1>
              </header>
                <div class="w3-container">
                    <h2 class="w3-black"><?php echo "<p style='color: white'>Error: $error </p>"; ?></h2>
                </div> 
            </div>
          </div>
        <script>
            document.getElement.ById('01').style.display="block";
        </script>
        <?php } ?>
        
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
    <script src="./js/logica.js"></script>
</body>
</html> 