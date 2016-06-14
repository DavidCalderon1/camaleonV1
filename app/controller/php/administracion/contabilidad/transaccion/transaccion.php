<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/transaccion.php');
    session_start();
    if(isset($_SESSION['usuario']))
    {
        $usuario = unserialize($_SESSION['usuario']);
        $get = json_decode(file_get_contents('php://input'));
        if(!isset($get->registrar)){$get->registrar=false;}else{$get->registrar = base64_decode($get->registrar);}
        if(!isset($get->loadData)){$get->loadData=false;}
        if(!isset($get->instanciar)){$get->instanciar=false;}
        if(!isset($get->actualizar)){$get->actualizar=false;}
        if(!isset($get->buscar)){$get->buscar=false;}
        //if(!isset($get->borrar)){$get->borrar=false;}

        if($get->registrar)
        {
            $get->fecha = base64_decode($get->fecha);
            $get->tipodoc = base64_decode($get->tipodoc);
            $get->descripcion = base64_decode($get->descripcion);
            if ($get->fecha != "" && $get->tipodoc != "" && $get->descripcion != "") {
                $conexion = new Conexion();
                $operation = Transaccion::registrar($get->tipodoc, $get->fecha, $get->descripcion , $conexion);
                if($operation['result']){
                    $operation['message'] = "Se registro correctamente la información.";
                    $log = Log::registro($usuario->getID(), "info", "Registro de información. - Transacción. {".$get->tipodoc.", ".$get->fecha.", ".$get->descripcion."}", $conexion);  
                    $transaccion = new Transaccion($operation['returning']['id'], $conexion);
                    $_SESSION['transaccion'] = serialize($transaccion);
                }elseif(!($operation['ejecution']))
                {
                    $log = Log::registro($usuario->getID(), "error", "Registro de información. - Transacción. {".$get->tipodoc.", ".$get->fecha.", ".$get->descripcion."}", $conexion);
                }
            }else{
                $operation['ejecution'] = true;
                $operation['result'] = false;
                $operation['message'] = "Por favor diligencie los todos los campos del formulario.";
            }
            echo json_encode($operation);
        }

        if ($get->loadData) {
            if(isset($_SESSION['transaccion'])){
                $transaccion = unserialize($_SESSION['transaccion']);
                $data['tipodoc'] = $transaccion->getTipoDocumento()->getID();
                $data['fecha'] = $transaccion->getFecha();
                $data['descripcion'] = $transaccion->getDescripcion();
                $movimientos = $transaccion->getMovimiento();
                $i=0;
                foreach ($movimientos as $key) {                 
                    $data['movimientos'][$i]['id'] = $key->getID();
                    $data['movimientos'][$i]['detalle'] = $key->getDetalle();
                    $data['movimientos'][$i]['sucursal'] = $key->getSucursal();


                    $req_tercero = $key->getTercero();
                    $req_activo = $key->getActivo();
                    if (!empty($req_activo)) {
                        $data['movimientos'][$i]['req'] = 'ACTIVO';
                        //$data['movimientos'][$i]['codtoa'] = $req_activo->getId();                    
                    }
                    if (!empty($req_tercero)){
                        $data['movimientos'][$i]['req'] = 'TERCERO';

                        $data['movimientos'][$i]['codtoa'] = $key->getTercero()->getId();
                    }
                    $data['movimientos'][$i]['cuenta'] = $key->getCuenta()->getID();
                    $data['movimientos'][$i]['debe'] = $key->getDebe();
                    $data['movimientos'][$i]['haber'] = $key->getHaber();
                    $i++;
                }
                //$data['movimientos'] = $transaccion->getMovimiento();
                
                $operation['ejecution'] = true;
                $operation['result'] = true;
                $operation['message'] = "Se cargo correctamente la información";
                $operation['data'] = $data;
                echo json_encode($operation);
            }
        }

        if ($get->buscar) {
            $get->fecha = base64_decode($get->fecha);
            $get->tipodoc = base64_decode($get->tipodoc);
            if ($get->fecha != "" || $get->tipodoc != "") {
                $conexion =new Conexion();
                $operation = Transaccion::buscar($get->fecha, $get->tipodoc, $conexion);
                $operation['message'] = "Se cargó correctamente la información.";
            }else{
                $operation['ejecution'] = true;
                $operation['result'] = false;
                $operation['message'] = "Por favor diligencie alguno de los campos del formulario para realizar la busqueda.";
            }
            echo json_encode($operation);
        }

        if ($get->instanciar) {
            
            $get->id_transaccion = base64_decode($get->id_transaccion);

            $conexion=new Conexion();
            $transaccion =new Transaccion($get->id_transaccion,$conexion);
            $_SESSION['transaccion'] = serialize($transaccion);

            $operation['ejecution'] = true;
            $operation['result'] = true;
            $operation['message'] = "Se cargo correctamente la información";
            echo json_encode($operation);
        }

        if ($get->actualizar) {
            if ($_SESSION['transaccion']) {

                $transaccion = unserialize($_SESSION['transaccion']);

                $get->fecha = base64_decode($get->fecha);
                $get->tipodoc = base64_decode($get->tipodoc);
                $get->descripcion = base64_decode($get->descripcion);

                if ($get->fecha != "" && $get->tipodoc != "" && $get->descripcion != "") {
                    $conexion =new Conexion();
                    $operation = $transaccion->modificar($get->tipodoc, $get->fecha, $get->descripcion, $conexion);
                    if(($operation['ejecution'])&&($operation['result']))
                    {
                        $operation['message'] = "Se actualizó correctamente la información.";
                           
                        $log = Log::registro($usuario->getID(), "info", "Actualización de información. - Transacción. {".$get->tipodoc.", ".$get->fecha.", ".$get->descripcion."}", $conexion);  
                        
                        $_SESSION['transaccion'] = serialize($transaccion);
                    }elseif(!($operation['ejecution']))
                    {
                        $log = Log::registro($usuario->getID(), "error", "Actualización de información. - Transacción. {".$get->tipodoc.", ".$get->fecha.", ".$get->descripcion."}", $conexion);
                    }

                }else{
                    $operation['ejecution'] = true;
                    $operation['result'] = false;
                    $operation['message'] = "Por favor diligencie los todos los campos del formulario.";
                }
                echo json_encode($operation);
            }
        }
    }
?>