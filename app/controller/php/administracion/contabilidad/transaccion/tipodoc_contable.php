<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/tipoDocumentoContable.php');

    session_start();

    if(isset($_SESSION['usuario'])){
    	
    	$usuario=unserialize($_SESSION['usuario']);

    	$get = json_decode(file_get_contents('php://input'));

        if($get->entity == "tipodoc"){

            $gbd = new Conexion();

            $operation = TipoDocumentoContable::listar($gbd);

            if($operation['result']){

                $i=0;

                foreach($operation['result'] as $fila){
                    $result[$i]['id'] = $fila['id'];
                    $result[$i]['nombre'] = $fila['descripcion'];
                    $i++;
                }

                $operation['result'] = $result;
                
            }

            echo json_encode($operation);

        }


    }

?>