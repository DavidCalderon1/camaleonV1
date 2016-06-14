<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/ciudad.php');

    session_start();

    if(isset($_SESSION['usuario'])){
    	
    	$usuario=unserialize($_SESSION['usuario']);

    	$get = json_decode(file_get_contents('php://input'));

        if($get->entity == "pais"){

            $gbd = new Conexion();

            if(isset($get->id)){

                $operation = Pais::listar($get->id, $gbd);

            }else{

                $operation = Pais::listar(null, $gbd);

            }

            echo json_encode($operation);

        }

        if($get->entity == "departamento"){

            $gbd = new Conexion();

            if(isset($get->id)){

                $operation = Departamento::listar($get->id, $gbd);

            }

            echo json_encode($operation);

        }

        if($get->entity == "ciudad"){

            $gbd = new Conexion();

            if(isset($get->id)){

                $operation = Ciudad::listar($get->id, $gbd);

            }

            echo json_encode($operation);

        }

    }

?>