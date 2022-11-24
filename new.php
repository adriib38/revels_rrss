<?php

    require_once('inc/red/bd.inc.php');  

    session_start();

    /**
     * Si existe el objeto user (Sesión iniciada)
     */
    if(isset($_SESSION['user'])){
        print_r($_SESSION);
        $sesionIniciada = true; 
    }else {
        echo 'NO INICIADA';
        header('Location: index.php');
        $sesionIniciada = false;
    }

    if(!empty($_POST)){
        if($_POST['texto'] != ''){
            $newRevel = new Revel(0, $_SESSION['user']->id, $_POST["texto"], 0, 0);
            //Se publica el revel
            $ultimoIndexAnyadido = insertRevel($newRevel);
            if($ultimoIndexAnyadido){
                //Se redirige a la página del revel
                header('Location: revel.php?id='.$ultimoIndexAnyadido.''); 
            }
        }else{
            $textoVacio = '<span class="red">No puedes publicar un revel vacío</span>';
        }
       
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New</title>

    <link rel="icon" type="image/x-icon" href="images/_logo.png">
    <script src="https://kit.fontawesome.com/92a45f44adX2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles\style.css">
</head>
<body>
    <?php
        require_once('inc/cabecera_logged.inc.php'); 
        $img = 'https://avatars.dicebear.com/api/avataaars/'.$_SESSION['user']->usuario.'.svg?b=%232e3436';
    ?>   
    <h2>Nuevo Revel</h2>
    <div class="publicar-revel">
        <div class="usuario">
            <img src="<?=$img?>">
            <h3><?=$_SESSION['user']->usuario?></h3>
        </div>
        <form action="#" method="post">
            <textarea name="texto" id="texto-nuevo-revel" placeholder="¿Qué está pasando?"></textarea>
            <input type="submit" id="publicar-revel" value="Revelar">
        <form>
    </div>
    <?=$textoVacio??'' ?>
</body>
</html>