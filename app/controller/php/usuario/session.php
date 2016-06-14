<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    $get = json_decode(file_get_contents('php://input'));

    if(!isset($get->validation)){$get->validation=false;}
    if(!isset($get->authentication)){$get->authentication=false;}else{$get->authentication = base64_decode($get->authentication);}
    if(!isset($get->logout)){$get->logout=false;}

    if($get->validation){

        session_start();

        if(isset($_SESSION['usuario'])){
            $operation['result'] = true;
            $operation['message'] = "Sesión de usuario activa.";
        }else{
            $operation['result'] = false;
            $operation['message'] = "No existe o ha terminado la sesión de usuario.";
        }
        
        $operation['ejecution'] = true;

        echo json_encode($operation);
        
    }

    if($get->authentication){

        $gbd = new Conexion();
        $usuario = new Usuario;

        $operation = $usuario->autenticar(base64_decode($get->nombre_usuario), $gbd);

        if($operation['ejecution']){

            if($operation['result']){

                if($usuario->getEstado()){

                    if($usuario->verificarPassword(base64_decode($get->cryptpass), $gbd)){

                        session_start();
                        $_SESSION['usuario'] = serialize($usuario);

                        $operation['message'] = "Bienvenido.";

                        $log = Log::registro($usuario->getID(), "info", "Acceso al sistema.", $gbd);
                        $operation['log'] = $log;


                    }else{

                        $operation['result']=false;
                        $operation['message'] = "La contraseña es incorrecta!";

                        $log = Log::registro($usuario->getID(), "error", "Denegacion de acceso al sistema. - Contraseña incorrecta. {".$get->cryptpass."}", $gbd);
                        $operation['log'] = $log;

                    }

                }else{

                    $operation['result']=false;
                    $operation['message'] = "Usuario inactivo!";

                    $log = Log::registro($usuario->getID(), "error", "Denegacion de acceso al sistema. - Usuario inactivo.", $gbd);
                    $operation['log'] = $log;

                }

            }else{

                $operation['message'] = "El nombre de usuario es invalido.";

            }

        }

        echo json_encode($operation);
        
    }

    if($get->logout){

        session_start();
        session_unset();
        session_destroy();

        $operation['ejecution'] = true;

        echo json_encode($operation);
        
    }

    /*if($get->authentication){
        $gbd = new Conexion();
        $a = Log::registro("Soporte", "info", "Prueba", $gbd);
        print_r($a);
    }*/

?>