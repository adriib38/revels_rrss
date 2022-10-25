<?php
    include('inc/Red-Objects.php');
    
    if(!empty($_POST)){
        if($red->login($_POST["mail"], $_POST["contrasenya"])){
            /*
             * Sesión inciada correctamente
             */
            echo 'login ok';
            header('Location: index.php');
        } else {
            $mensajeInicioFallido = '<p class="red">Inicio fallido</p>';
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Revels</title>

        <link rel="stylesheet" href="styles/style.css">
        
    </head>
    <body>

        <?php include('inc/cabecera.inc.php'); ?>

        <div class="mrg-100">
            <div class="centrado">
                
                <h1>Login</h1>
                <article>
                    <form method="post" action="#" class="form-auth bg-azul">
                        <input type="text" name="mail" placeholder="Mail" value="<?=$_POST['mail']??'' ?>">
                        <br>
                        <input type="password" name="contrasenya" placeholder="Contraseña" value="<?=$_POST['contrasenya']??'' ?>">
                        <br>
                        <input type="submit" value="Login">
                    </form>
                </article>
                <?=$mensajeInicioFallido??'' ?>
                <p><a href="#">¿Olvidaste tu contraseña?</a></p>
                <br>
                <p>¿Eres nuevo?<a href="registro.php">Registrarse</a></p>
            </div>
        </div>
    
        <?php include('inc/footer.inc.php'); ?>
    </body>
</html>