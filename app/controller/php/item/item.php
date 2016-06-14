<?php 
	/**
	* @autor : Steven Medina y Yordy Gelvez
	* @editor : Sublime Text 3
	* @metodo : PHP en el controlador con PDO
	* @descripcion : Desarrollo del controlador en PHP para registrar, modificar, cargar, borrar
	*/

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/item/item.php');

	session_start();

	if (isset($_SESSION['usuario'])) {
		$usuario = unserialize($_SESSION['usuario']);

		$get = json_decode(file_get_contents('php://input'));

		if (!isset($get->registrar)) {$get->registrar=false;}else{$get->registrar=base64_decode($get->registrar);}
		if (!isset($get->loadData)) {$get->loadData=false;}
		if (!isset($get->instanciar)) {$get->instanciar=false;}
		if (!isset($get->actualizar)) {$get->actualizar=false;}
		if (!isset($get->buscar)) {$get->buscar=false;}
		if (!isset($get->entity)){ $get->entity = false;}

		if ($get->registrar) {
			$get->tipo = base64_decode($get->tipo);
			$get->referencia = strtoupper(base64_decode($get->referencia));
			$get->nombre = strtoupper(base64_decode($get->nombre));
			$get->descripcion = strtoupper(base64_decode($get->descripcion));
			$get->unidad = base64_decode($get->unidad);
			$get->iva = base64_decode($get->iva);

			$result_c = "";

			/*$validacionNombre = count($get->c_nombre);
			$validacionDescripcion = count($get->c_descripcion);*/

			$validacionNombre = is_array($get->c_nombre);
			$validacionDescripcion = is_array($get->c_descripcion);

			$vacio = false;
			$bandera = 0;
			
			if ($validacionNombre && $validacionDescripcion) {
				$bandera = 1;
			}else if(($validacionNombre && !$validacionDescripcion) || (!$validacionNombre && $validacionDescripcion)){
				$vacio = true;
			}else{
				$bandera = 2;
			}			

			if ($bandera == 1) {
				/*$i = 0;*/
				$camposlength = count($get->c_nombre);
				$result_c = "{";
				for ($i=0; $i < $camposlength ; $i++) { 
					$c_nombre = base64_decode($get->c_nombre[$i]);
					$c_descripcion = base64_decode($get->c_descripcion[$i]);
					if ($c_nombre != "" && $c_descripcion != "") {
						if($i != ($camposlength-1)){
							$result_c .= "{" . $c_nombre . ", " . $c_descripcion . "},";
						}else{
							$result_c .= "{" . $c_nombre . ", " . $c_descripcion . "}";
						}
						
					}else {
						$vacio = true;
					}
				}
				$result_c .= "}";

				/*foreach ($get->c_nombre as $key) {
					$valor = base64_decode($key);
					if ($valor != "") {
						$result_c['nombre'][$i] = $valor;
					}else {
						$vacio = true;
					}
					$i++;
				}

				$i = 0;
				foreach ($get->c_descripcion as $key) {
					$valor = base64_decode($key);
					if ($valor != "") {
						$result_c['descripcion'][$i] = $valor;
					}else {
						$vacio = true;
					}
					$i++;
				}*/

			}else if($bandera == 2) {
				$get->c_nombre = base64_decode($get->c_nombre);
				$get->c_descripcion = base64_decode($get->c_descripcion);

				if ($get->c_nombre != "" &&  $get->c_descripcion != "") {
					$result_c = "{{" . $get->c_nombre . ", " . $get->c_descripcion . "}}";
				} else {
					$vacio = true;
				}
			}

			$data = $result_c;

			if ($get->referencia != "" && $get->nombre != "" && $get->descripcion != "" && $get->unidad != "" && $get->iva != "" && !$vacio) {
				$conexion = new Conexion();

				$operation = Materia_Prima::registrar($get->nombre, $get->descripcion, $data, $conexion);

				if($operation['ejecution'] && $operation['result']) {
					$operation = Item::registrar($operation['returning']['id'], $get->referencia, $get->iva, $get->unidad, $get->tipo, $conexion);
					if($operation['ejecution'] && $operation['result']){
						$item = new Item($operation['returning']['id'], $conexion);
						$item->instanciarMateriaprima($conexion);

						$operation['message'] = "Se registro correctamente la información.";

						$log = Log::registro($usuario->getID(), "info", "Registro de información - Item. {".$get->referencia.", ". $get->iva.", ". $get->unidad.", ".$get->tipo."}", $conexion);

						$log = Log::registro($usuario->getID(), "info", "Registro de información - Materia Prima. {".$get->nombre.", ". $get->descripcion.", ". $data."}", $conexion);
						
						$_SESSION['item'] = serialize($item);
					}
				}
				
			}
			else {
				$operation['ejecution'] = true;
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie todos los campos del formulario.";
			}
			echo json_encode($operation);
		}

		if ($get->loadData) {
			if (isset($_SESSION['item'])) {
				$item = unserialize($_SESSION['item']);

				// Datos Item
				$data['id'] = $item->getID();
				$data['referencia'] = $item->getReferencia();
				$data['iva'] = $item->getPorcentiva();
				$data['unidad'] = $item->getUnidadmedida();

				// Datos Materia Prima
				$mp = $item->getMateriaprima();
				$data['id'] = $mp->getID();
				$data['nombre'] = $mp->getNombre();
				$data['descripcion'] = $mp->getDescripcion();

				$array = explode('},{', $mp->getCaracteristicas());
				for($i=0; $i<count($array);$i++)
				{
					$array[$i] = trim($array[$i], '{{');$array[$i] = trim($array[$i], '{');
					$array[$i] = trim($array[$i], '}}');$array[$i] = trim($array[$i], '}');

					$array[$i] = str_getcsv($array[$i]);
				}

				$data['caracteristica'] = $array;

				$operation['ejecution'] = true;
				$operation['result'] = true;
				$operation['message'] = "Se cargo correctamente la información.";
				$operation['data'] = $data;

				echo json_encode($operation);
			}
		}

		if ($get->actualizar) {
			$get->tipo = base64_decode($get->tipo);
			$get->referencia = strtoupper(base64_decode($get->referencia));
			$get->nombre = strtoupper(base64_decode($get->nombre));
			$get->descripcion = strtoupper(base64_decode($get->descripcion));
			$get->unidad = base64_decode($get->unidad);
			$get->iva = base64_decode($get->iva);

			if (isset($_SESSION['item'])) {
				$item = unserialize($_SESSION['item']);

				$result_c = "";

				$validacionNombre = is_array($get->c_nombre);
				$validacionDescripcion = is_array($get->c_descripcion);

				$vacio = false;
				$bandera = 0;
				
				if ($validacionNombre && $validacionDescripcion) {
					$bandera = 1;
				} else if(($validacionNombre && !$validacionDescripcion) || (!$validacionNombre && $validacionDescripcion)) {
					$vacio = true;
				} else {
					$bandera = 2;
				}			

				if ($bandera == 1) {
					$camposlength = count($get->c_nombre);
					$result_c = "{";
					for ($i=0; $i < $camposlength ; $i++) { 
						$c_nombre = base64_decode($get->c_nombre[$i]);
						$c_descripcion = base64_decode($get->c_descripcion[$i]);
						if ($c_nombre != "" && $c_descripcion != "") {
							if($i != ($camposlength-1)){
								$result_c .= "{" . $c_nombre . ", " . $c_descripcion . "},";
							}else{
								$result_c .= "{" . $c_nombre . ", " . $c_descripcion . "}";
							}
							
						}else {
							$vacio = true;
						}
					}
					$result_c .= "}";

				} else if($bandera == 2) {
					$get->c_nombre = base64_decode($get->c_nombre);
					$get->c_descripcion = base64_decode($get->c_descripcion);

					if ($get->c_nombre != "" &&  $get->c_descripcion != "") {
						$result_c = "{{" . $get->c_nombre . ", " . $get->c_descripcion . "}}";
					} else {
						$vacio = true;
					}
				}

				$data = $result_c;

				if ($get->referencia != "" && $get->nombre != "" && $get->descripcion != "" && $get->unidad != "" && $get->iva != "" && $get->tipo != "" && !$vacio ) {
					$conexion = new Conexion();
					$item = new Item($item->getID(), $conexion);
					$item->instanciarMateriaprima($conexion);
					$mp = $item->getMateriaprima();
					$operation = $mp->modificar($get->nombre, $get->descripcion, $data, $conexion);
					if($operation['ejecution'] && $operation['result']){
						$operation = $item->modificar($get->referencia, $get->iva, $get->unidad, $get->tipo, $conexion);
						if ($operation['ejecution'] && $operation['result']) {
							/*$item->instanciarMateriaprima($conexion);
							$mp = $item->getMateriaprima();*/

							$operation['message'] = "Se actualizó correctamente la información.";

							$log = Log::registro($usuario->getID(), "info", "Actualización de información - Item. {".$get->referencia.", ". $get->iva.", ". $get->unidad.", ".$get->tipo."}", $conexion);

							$log = Log::registro($usuario->getID(), "info", "Actualización de información - Materia Prima. {".$get->nombre.", ". $get->descripcion.", ". $data."}", $conexion);
						}
					}
					$_SESSION['item'] = serialize($item);
				}
			}
			else {
				$operation['ejecution'] = true;
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie todos los campos del formulario.";
			}
			echo json_encode($operation);
		}

		if ($get->buscar) {
			$get->tipo = base64_decode($get->tipo);
			$get->parametro = strtoupper(base64_decode($get->parametro));
			$conexion =new Conexion();
			$operation = Item::buscar($get->parametro, $get->tipo, $conexion);

			if(count($operation['result'])==1){
	            $operation['message'] = "Se encontro ".count($operation['result'])." registro.";
	        }
	        elseif(count($operation['result'])>1){
	            $operation['message'] = "Se encontraron ".count($operation['result'])." registros.";
	        }
	        else{
	            $operation['message'] = "No se encuentran registros con los parametros ingresados.";
	        }
			echo json_encode($operation);
		}

		if ($get->instanciar) {
			$get->id = base64_decode($get->id);

			$conexion = new Conexion();

			$item = new Item($get->id, $conexion);
			$item->instanciarMateriaprima($conexion);
			$_SESSION['item'] = serialize($item);

			$operation['message'] = "Se cargo correctamente la información.";
			$operation["ejecution"] = true;
        	$operation['result'] = true;
        	
          	echo json_encode($operation);
		}

		if($get->entity == "item"){

			$conexion = new Conexion();
			$operation = Item::buscar('', 'materia_prima', $conexion);
			if($operation['result']){
				$i=0;
                foreach($operation['result'] as $fila){
                    $result[$i]['id'] = $fila['itm_id'];
                    $result[$i]['nombre'] = $fila['mp_nombre'];
                    $i++;
                }

                $operation['result'] = $result;
			}

            echo json_encode($operation);

        }


	}
	
?>