<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/inventario.php');

	session_start();

	if (isset($_SESSION['usuario'])) {
		$usuario = unserialize($_SESSION['usuario']);

		$get = json_decode(file_get_contents('php://input'));

		if (!isset($get->registrar)) {$get->registrar=false;}else{$get->registrar=base64_decode($get->registrar);}
		if (!isset($get->loadData)) {$get->loadData=false;}
		if (!isset($get->instanciar)) {$get->instanciar=false;}
		if (!isset($get->actualizar)) {$get->actualizar=false;}
		if (!isset($get->buscar)) {$get->buscar=false;}
		if (!isset($get->reporte)) {$get->reporte=false;}

		if ($get->registrar) {
			$get->fecha = base64_decode($get->fecha);
			$get->tipo = base64_decode($get->tipo);
			$get->item = base64_decode($get->item);
			$get->cantidad = base64_decode($get->cantidad);
			$get->valorunit = base64_decode($get->valorunit);

			if ($get->fecha != "" && $get->tipo != "" && $get->item != "" && $get->cantidad != "" && $get->valorunit != "") {
				$conexion = new Conexion();
				$operation = Inventario::registrar($get->fecha, $get->item, $get->tipo, $get->cantidad, $get->valorunit, $conexion);			
				if($operation['ejecution'] && $operation['result']){
					$inventario = new Inventario($operation['returning']['id'], $conexion);					
					$operation['message'] = "Se registró correctamente la información.";
					$log = Log::registro($usuario->getID(), "info", "Registro de Iventario. {".$get->fecha.", ". $get->item.", ". $get->tipo.", ".$get->cantidad.", ".$get->valorunit."}", $conexion);
				}
				$_SESSION['inventario'] = serialize($inventario);
			}else{
				$operation['ejecution'] = true;
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie todos los campos del formulario.";
			}
			echo json_encode($operation);
		}

		if ($get->loadData) {
			if (isset($_SESSION['inventario'])) {
				$inventario = unserialize($_SESSION['inventario']);

				$data['id'] = $inventario->getID();
				$data['fecha'] = $inventario->getFecha();
				$data['item'] = $inventario->getItem()->getID();
				$data['tipo'] = $inventario->getEntradaSalida();
				$data['cantidad'] = $inventario->getCantidad();
				$data['valorunit'] = $inventario->getValorUnitario();

				$operation['ejecution'] = true;
				$operation['result'] = true;
				$operation['message'] = "Se cargo correctamente la información.";
				$operation['data'] = $data;

				echo json_encode($operation);
			}
		}

		if ($get->actualizar) {
			$get->fecha = base64_decode($get->fecha);
			$get->tipo = base64_decode($get->tipo);
			$get->item = base64_decode($get->item);
			$get->cantidad = base64_decode($get->cantidad);
			$get->valorunit = base64_decode($get->valorunit);

			if (isset($_SESSION['inventario'])) {
				$inventario = unserialize($_SESSION['inventario']);

				if ($get->fecha != "" && $get->tipo != "" && $get->item != "" && $get->cantidad != "" && $get->valorunit != "") {
					$conexion =new Conexion();
					$operation = $inventario->modificar($get->fecha, $get->item, $get->tipo, $get->cantidad, $get->valorunit, $conexion);
					if($operation['ejecution'] && $operation['result']){				
						$operation['message'] = "Se actualizó correctamente la información.";
						$log = Log::registro($usuario->getID(), "info", "Actualización de Iventario. {".$get->fecha.", ". $get->item.", ". $get->tipo.", ".$get->cantidad.", ".$get->valorunit."}", $conexion);
					}
					$_SESSION['inventario'] = serialize($inventario);
				}else{
					$operation['ejecution'] = true;
					$operation['result'] = false;
					$operation['message'] = "Por favor diligencie todos los campos del formulario.";
				}
				echo json_encode($operation);

			}
		}

		if ($get->buscar) {
			$get->fecha = base64_decode($get->fecha);
			$get->item = base64_decode($get->item);

			if ($get->fecha != "" && $get->item != "") {
				$conexion = new Conexion();
				$operation = Inventario::buscar($get->item, $get->fecha, $conexion);

				if(count($operation['result'])==1){
		            $operation['message'] = "Se encontro ".count($operation['result'])." registro.";
		        }
		        elseif(count($operation['result'])>1){
		            $operation['message'] = "Se encontraron ".count($operation['result'])." registros.";
		        }
		        else{
		            $operation['message'] = "No se encuentran registros con los parametros ingresados.";
		        }
				
			}else{
				$operation['ejecution'] = true;
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie los campos de busqueda.";
			}
   
			echo json_encode($operation);
		}

		if ($get->instanciar) {
			$get->id = base64_decode($get->id);

			$conexion = new Conexion();

			$inventario = new Inventario($get->id, $conexion);
			$_SESSION['inventario'] = serialize($inventario);

			$operation['message'] = "Se cargo correctamente la información.";
			$operation["ejecution"] = true;
        	$operation['result'] = true;
        	
          	echo json_encode($operation);
		}

		if ($get->reporte) {
			$get->item = base64_decode($get->item);

			if ($get->item != "") {
				$conexion = new Conexion();
				$operation = Inventario::reporteItem($get->item, $conexion);

				if(count($operation['result'])==1){
		            $operation['message'] = "Se encontro ".count($operation['result'])." registro.";
		        }
		        elseif(count($operation['result'])>1){
		            $operation['message'] = "Se encontraron ".count($operation['result'])." registros.";
		        }
		        else{
		            $operation['message'] = "No se encuentran registros con los parametros ingresados.";
		        }
				
			}else{
				$operation['ejecution'] = true;
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie los campos de busqueda.";
			}
   
			echo json_encode($operation);
		}
	}
?>