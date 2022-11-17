<?php
  
    require_once('inc/Revel.inc.php');
    require_once('inc/User.inc.php');
    require_once('inc/Comment.inc.php');

    $user = 'revel';
    $password = 'lever';
    $bdName = 'revels';
    $host = 'localhost';
    $port = '3306';

    //Información de la base de datos
    $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$bdName.'';          
    $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    function todosLosUsuarios(){

        $conexion = new PDO($dsn, $user, $password, $opciones);
        
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM `users` WHERE 1;');
        unset($conexion);
        
        $usuarios = $resultado->fetch();

    }

    /**
     * Devuelve usuario por id.
     */
    function selectUserById($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $returnUser = null;

        try{
            //Consulta SELECT
            $resultado = $conexion->query('SELECT * FROM `users` WHERE `id` LIKE '.$id.'');
            unset($conexion);

            $filas = $resultado->rowCount();
            if($filas == 0){ 
                return false;
            };

            $userObtenido = $resultado->fetch();
            $usr = new User($userObtenido['id'], $userObtenido['usuario'], $userObtenido['contrasenya'], $userObtenido['email']);
            return $usr;
        }catch(Exception $e){
            return false; 
        }

        return false;
    }

    /**
     * Devuelve usuario por nombre ('usuario').
     */
    function selectUserByUserName($name){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $user = null;
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM users WHERE usuario LIKE "'.$name.'"');
        unset($conexion);

        $user = $resultado->fetch();
        
        return $user;
    }

    /**
     * Devuelve usuario por email ('email').
     */
    function selectUserByEmail($email){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $user = null;
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM users WHERE email LIKE "'.$email.'"');
        unset($conexion);

        $user = $resultado->fetch();
 
        return $user;
    }

    /**
     * Devuelve un array de Revels de un usuario por id.
     */
    function selectRevelsFromUser($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        if(selectUserById($id) == null) return array();

        $revels = array();
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM revels WHERE userid LIKE "'.$id.'"');
        unset($conexion);

        while($revelObtenido = $resultado->fetch()){
            $rev = new Revel($revelObtenido['id'], $revelObtenido['userid'], $revelObtenido['texto'], $revelObtenido['fecha']);
            array_push($revels, $rev);
        }
    
        return $revels;
    }

    /**
    * Devuelve un array de Users que sigue el Usuario del $id.
    */
    function selectFollowsFromUser($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        if(selectUserById($id) == null) return array();

        $followers = array();
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM `follows` WHERE userid LIKE "'.$id.'"');
        unset($conexion);

        while($res = $resultado->fetch()){
            $followed = selectUserById($res['userfollowed']);
            $usr = new User($followed->id, $followed->usuario, $followed->contrasenya, $followed->email);
            array_push($followers, $usr);
        }
        return $followers;
    }

    /**
    * Devuelve un objeto Revel por id.
    */
    function selectRevel($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);
        
        try{
            //Consulta SELECT
            $resultado = $conexion->query('SELECT * FROM `revels` WHERE id LIKE "'.$id.'"');
            unset($conexion);

            $filas = $resultado->rowCount();
            if($filas == 0){ 
                return false;
            };

            $revelObtenido = $resultado->fetch();
            $rev = new Revel($revelObtenido['id'], $revelObtenido['userid'], $revelObtenido['texto'], $revelObtenido['fecha']);
            return $rev;
        }catch(Exception $e){
            return false;
        }
        
    }

    /**
    * Devuelve un objeto Comment por id.
    */
    function selectComment($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);
        
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM `comments` WHERE id LIKE "'.$id.'"');
        unset($conexion);

        $filas = $resultado->rowCount();
        if($filas == 0){ 
            return false;
        };

        $commentObtenido = $resultado->fetch();
        $comment = new Comment($commentObtenido['id'], $commentObtenido['revelid'], $commentObtenido['userid'], $commentObtenido['fecha'], $commentObtenido['texto']);
        
        return $comment;
    }

    /**
    * Devuelve los comments de un revel desde el id de un revel
    */
    function selectCommentsFromRevel($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);
        
        if(!selectRevel($id)){
            return array();
        }

        $comments = array();
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM `comments` WHERE revelid LIKE "'.$id.'"');
        unset($conexion);

        while($com = $resultado->fetch()){
            $comment = new Comment($com['id'], $com['revelid'], $com['userid'], $com['fecha'], $com['texto']);
            array_push($comments, $comment);
        }
        return($comments);
    }

    /**
    * Devuelve los revels de un usuario por id
    */
    function selectRevelsForUser($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);
        
        if(selectUserById($id) == null){
            return array();
        }

        $revels = array();
        //Consulta SELECT
        $resultado = $conexion->query('SELECT * FROM `revels` WHERE userid LIKE "'.$id.'"');
        unset($conexion);

        while($revelsObtenidos = $resultado->fetch()){
            $revel = new Revel($revelsObtenidos['id'], $revelsObtenidos['userid'], $revelsObtenidos['texto'], $revelsObtenidos['fecha']);
            array_push($revels, $revel);
        }
        return($revels);
    }

    /**
    * Inserta un usuario
    */
    function insertUser($userCrear){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        try{
            $consulta = $conexion->prepare('INSERT INTO users
                (usuario, contrasenya, email) 
                VALUES (?, ?, ?);');
            $consulta->bindParam(1, $userCrear->usuario);
            $consulta->bindParam(2, $userCrear->contrasenya);
            $consulta->bindParam(3, $userCrear->email);

            $consulta->execute();

            unset($conexion);

            return true;
        }catch(PDOException $e){
            return false;
        }   
    }

    /**
    * Actualizar un usuario
    */
    function updateUser($userActualizar){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        print_r($userActualizar);
        $idActualizar = $userActualizar->id;
        try{
            $consulta = $conexion->prepare('UPDATE users
                SET usuario=?, contrasenya=?, email=? 
                WHERE users.id = '.$idActualizar.'');
            $consulta->bindParam(1, $userActualizar->usuario);
            $consulta->bindParam(2, $userActualizar->contrasenya);
            $consulta->bindParam(3, $userActualizar->email);

            $consulta->execute();

            unset($conexion);

            return true;
        }catch(PDOException $e){
            return false;
        }   
    }

    /**
    * Si el email coincide con la contraseña devuelve el usuario; Si no devuelve false
    *
	* @deprecated desde login2
	*/
    function login($email, $pass){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        try {
            $resultado = $conexion->query('SELECT * FROM `users` WHERE email like "'.$email.'" AND contrasenya LIKE "'.$pass.'";');
            unset($conexion);
            
            $filas = $resultado->rowCount();
            if($filas != 0){
                $usrObtenido = $resultado->fetch();
              
            }else{
                return false;
            }
                   
        }catch(PDOException $e){
            return false;
        }
    }

    /**
     * Login con contraseñas encriptadas
     */
    function login2($email, $pass){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $resultado = $conexion->query('SELECT contrasenya, id FROM users WHERE email like "'.$email.'";');
        unset($conexion);

        $filas = $resultado->rowCount();
        if($filas != 0){
            $usuario = $resultado->fetch();
            if(password_verify($pass, $usuario["contrasenya"])){
                return $usuario["id"];
            }else {
                return false;
            }
        }

    }

    /**
    * Crea un nuevo revel
    */
    function insertRevel($revel){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $hoy = date_format(date_create(), 'Y-m-d H:i:s');
        try{
            $consulta = $conexion->prepare('INSERT INTO revels
                (userid, texto, fecha) 
                VALUES (?, ?, ?);');
            $consulta->bindParam(1, $revel->userid);
            $consulta->bindParam(2, $revel->texto);
            $consulta->bindParam(3, $revel->$hoy);

            $consulta->execute();

            unset($conexion);

            return true;
        }catch(PDOException $e){
            print_r($e);
            return false;
        }   
    }

    /**
    * Crea un comentario
    */
    function insertComments($comment){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $hoy = date_format(date_create(), 'Y-m-d H:i:s');
        try{
            $consulta = $conexion->prepare('INSERT INTO comments
                (revelid, userid, texto, fecha) 
                VALUES (?, ?, ?, ?);');
            $consulta->bindParam(1, $comment->revelid);
            $consulta->bindParam(2, $comment->userid);
            $consulta->bindParam(3, $comment->texto);
            $consulta->bindParam(4, $comment->$hoy);

            $consulta->execute();

            unset($conexion);
          
            return true;
        }catch(PDOException $e){
            return false;
        }   
    }

    //NO FUNCIONA
    function leSigue($followed, $follower){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        try{
            $resultado = $conexion->query('SELECT * FROM follows WHERE userid = '.$follower.' AND userfollowed = '.$followed.';');
            unset($conexion);
            
            $filas = $resultado->rowCount();
            
            print_r($filas);
            if($filas > 0){
                return true;
            }else{
                return false;
            }
            
      
        }catch(PDOException $e){
           print_r($e);
        }   
    }

    /**
    * Crea una relacion follow
    */
    function insertFollow($follower, $followed){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        if(leSigue($followed, $follower)) { 
            echo 'Ya le sigues';
            return false; 
        }else{

            try{
                $consulta = $conexion->prepare('INSERT INTO follows
                    (userid, userfollowed) 
                    VALUES (?, ?);');
                $consulta->bindParam(1, $follower);
                $consulta->bindParam(2, $followed);

                $consulta->execute();

                unset($conexion);
            
                return true;
            }catch(PDOException $e){
                return false;
            }   
        }
    }

    /**
    * Crea una relacion follow.
    */
    function searchUsers($user_){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        $usuarios = array();

        try{
            $resultado = $conexion->query('SELECT * FROM `users` WHERE usuario LIKE "%'.$user_.'%";');
            unset($conexion);
            
            $usuarios = array();

            $filas = $resultado->rowCount();
            if($filas != 0){
                while($usuariosObtenidos = $resultado->fetch()){
                    $usr = new User($usuariosObtenidos['id'], $usuariosObtenidos['usuario'], $usuariosObtenidos['contrasenya'], $usuariosObtenidos['email']);
                    array_push($usuarios, $usr);
                }
                return $usuarios;
            } else {
                return false;
            }

        }catch(PDOException $e){
            return false;
        }   
    }

    /**
    * Elimina un commentario por su id.
    */
    function deleteComment($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        if(!selectComment($id)){
            return array();
        }
        try{
            $consulta = $conexion->prepare('DELETE FROM comments
                WHERE id LIKE ?');
            $consulta->bindParam(1, $id);

            $consulta->execute();

            unset($conexion);
          
            return true;
        }catch(PDOException $e){
            return false;
        }   
    }

    /**
    * Elimina un revel por su id.
    */
    function deleteRevel($id){
        global $dsn, $user, $password, $opciones;
        $conexion = new PDO($dsn, $user, $password, $opciones);

        if(!selectRevel($id)){
            return array();
        }
        try{
            $consulta = $conexion->prepare('DELETE FROM revels
                WHERE id LIKE ?');
            $consulta->bindParam(1, $id);

            $consulta->execute();

            unset($conexion);
            
            return true;
        }catch(PDOException $e){
            return false;
        }   
    }



?>

