<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/transaccion.php');
    session_start();
    if(isset($_SESSION['usuario']) && isset($_SESSION['transaccion']))
    {
    	$usuario = unserialize($_SESSION['usuario']);
    	$transaccion = unserialize($_SESSION['transaccion']);
    	$get = json_decode(file_get_contents('php://input'));
        if(!isset($get->registrar)){$get->registrar=false;}else{$get->registrar = base64_decode($get->registrar);}
        if(!isset($get->loadData)){$get->loadData=false;}
        if(!isset($get->instanciar)){$get->instanciar=false;}
        if(!isset($get->actualizar)){$get->actualizar=false;}
        if(!isset($get->buscar)){$get->buscar=false;}
        //if(!isset($get->borrar)){$get->borrar=false;}
        if($get->registrar)
        {
        	$get->detalle = base64_decode($get->detalle);
			$get->sucursal = base64_decode($get->sucursal);
			$get->req = base64_decode($get->req);
			$get->codtoa = base64_decode($get->codtoa);
			$get->cuenta = base64_decode($get->cuenta);
			$get->debe = base64_decode($get->debe);
			$get->haber = base64_decode($get->haber);
			if ($get->detalle != "" && $get->sucursal != "" && $get->req != "" && $get->codtoa != "" && $get->cuenta != "" && $get->debe != "" && $get->haber != "") {
				$conexion = new Conexion();
                if ($get->req == "tercero") {
                    $operation = MovimientoContable::registrar($transaccion->getID(), $get->sucursal, $get->detalle, $get->cuenta, $get->codtoa, 0, $get->debe, $get->haber, $conexion);   
                    $log = Log::registro($usuario->getID(), "info", "Registro de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", ".$get->codtoa.", 0, ".$get->debe.", ".$get->haber."}", $conexion);
                }else{
                    $operation = MovimientoContable::registrar($transaccion->getID(), $get->sucursal, $get->detalle, $get->cuenta, 0, $get->codtoa, $get->debe, $get->haber, $conexion);
                    $log = Log::registro($usuario->getID(), "info", "Registro de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", 0, ".$get->codtoa.", ".$get->debe.", ".$get->haber."}", $conexion);
                }
				if($operation['result']){
                    $operation['message'] = "Se registro correctamente la información.";
					$movimiento = new MovimientoContable($operation['returning']['id'], $conexion);
                    $_SESSION['movimiento'] = serialize($movimiento);
				}elseif (!$operation['result']) {
                    $log = Log::registro($usuario->getID(), "error", "Registro de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", ".$get->codtoa.", ".$get->debe.", ".$get->haber."}", $conexion);
                }
			}else{
				$operation['ejecution'] = true;
                $operation['result'] = false;
              	$operation['message'] = "Por favor diligencie los todos los campos del formulario.";
			}
			echo json_encode($operation);
        }

        if ($get->loadData) {
            if (isset($_SESSION['movimiento'])) {

                $conexion = new Conexion();
                
                $movimiento = unserialize($_SESSION['movimiento']);

                $data['detalle'] = $movimiento->getDetalle();
                $data['sucursal'] = $movimiento->getSucursal();
                $req_tercero = $movimiento->getTercero();
                $req_activo = $movimiento->getActivo();
                if (!empty($req_activo)) {
                    $data['req'] = 'activo';
                    //$data['codtoa'] = $movimiento->getActivo()->getId();                    
                }
                if(!empty($req_tercero)){
                    $data['req'] = 'tercero';
                    $tercero = $movimiento->getTercero();
                    $data['codtoa'] = $tercero->getId();
                }
                //$data['codtoa'] = $movimiento->getCo;
                $data['cuenta'] = $movimiento->getCuenta()->getID();
                $data['debe'] = $movimiento->getDebe();
                $data['haber'] = $movimiento->getHaber();

                $operation['ejecution'] = true;
                $operation['result'] = true;
                $operation['message'] = "Se cargo correctamente la información";
                $operation['data'] = $data;
                
                echo json_encode($operation);
            }
        }

        if ($get->instanciar) {
            $get->id_movimiento = base64_decode($get->id_movimiento);

            $conexion =new Conexion();
            $movimiento = new MovimientoContable($get->id_movimiento, $conexion);

            $_SESSION['movimiento'] = serialize($movimiento);
            
            $operation['ejecution'] = true;
            $operation['result'] = true;
            $operation['message'] = "Se cargo correctamente la información";
            echo json_encode($operation);
        }

        if ($get->actualizar) {
            if ($_SESSION['movimiento']) {
                $movimiento = unserialize($_SESSION['movimiento']);
                $get->detalle = base64_decode($get->detalle);
                $get->sucursal = base64_decode($get->sucursal);
                $get->req = base64_decode($get->req);
                $get->codtoa = base64_decode($get->codtoa);
                $get->cuenta = base64_decode($get->cuenta);
                $get->debe = base64_decode($get->debe);
                $get->haber = base64_decode($get->haber);
                if ($get->detalle != "" && $get->sucursal != "" && $get->req != "" && $get->codtoa != "" && $get->cuenta != "" && $get->debe != "" && $get->haber != "") {
                    $conexion = new Conexion();
                    if ($get->req == "tercero") {
                        $operation = $movimiento->modificar($transaccion->getID(), $get->sucursal, $get->detalle, $get->cuenta, $get->debe, $get->haber, $conexion);
                        if($operation['result']){
                            $movimiento->modificarTercero($get->codtoa,$conexion);
                            $log = Log::registro($usuario->getID(), "info", "Actualización de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", ".$get->codtoa.", 0, ".$get->debe.", ".$get->haber."}", $conexion);
                        }elseif (!$operation['result']) {
                            $log = Log::registro($usuario->getID(), "error", "Actualización de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", ".$get->codtoa.", 0, ".$get->debe.", ".$get->haber."}", $conexion);
                        }
                    }else{
                        $operation = $movimiento->modificar($transaccion->getID(), $get->sucursal, $get->detalle, $get->cuenta, $get->debe, $get->haber, $conexion);
                        if($operation['result']){
                            //$movimiento->modificarTercero($get->codtoa,$conexion);
                            $log = Log::registro($usuario->getID(), "info", "Actualización de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", 0, ".$get->codtoa.", ".$get->debe.", ".$get->haber."}", $conexion);
                        }elseif (!$operation['result']) {
                            $log = Log::registro($usuario->getID(), "error", "Actualización de información. - Movimiento Contable. {".$transaccion->getID().", ".$get->sucursal.", ".$get->detalle.", ".$get->cuenta.", 0, ".$get->codtoa.", ".$get->debe.", ".$get->haber."}", $conexion);
                        }
                    }
                    if($operation['result']){
                        $operation['message'] = "Se registro correctamente la información.";
                        $movimiento = new MovimientoContable($movimiento->getID(), $conexion);
                        $_SESSION['movimiento'] = serialize($movimiento);
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