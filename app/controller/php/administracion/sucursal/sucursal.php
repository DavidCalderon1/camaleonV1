<?php  
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/sucursal.php');

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

	session_start();

    if(isset($_SESSION['usuario']))
    {
    	$usuario = unserialize($_SESSION['usuario']);

    	$get = json_decode(file_get_contents('php://input'));

    	if (!isset($get->registrar)) { $get->registrar = false; }else{ $get->registrar = base64_decode($get->registrar);}
    	if (!isset($get->loadData)) { $get->loadData = false; }
		if (!isset($get->actualizar)) { $get->actualizar = false; }
		if (!isset($get->buscar)){ $get->buscar = false; } 
		if (!isset($get->instanciar)){ $get->instanciar = false; }
		if (!isset($get->entity)){ $get->entity = false; }

		if ($get->registrar) 
		{
			
			if ($get->nombre != "" && $get->pais != "" && $get->departamento != "" && $get->ciudad != "" && $get->dir != "" && $get->tel != "") {
				$get->nombre =	strtoupper(base64_decode($get->nombre));
				$get->ciudad =	base64_decode($get->ciudad);
				$get->dir =	strtoupper(base64_decode($get->dir));
				$get->tel =	base64_decode($get->tel);

				$conexion = new Conexion();
				$sucursal = new Sucursal();
				$operation = $sucursal->registrar($get->nombre, $get->ciudad, $get->dir, $get->tel, $conexion);

				if (($operation['ejecution']) && ($operation['result'])) {

					$operation['message'] = "La informacion se registro exitosamente.";
					$log = Log::registro($usuario->getID(), "info","Registro de información. - Sucursal. {".$get->nombre.",".$get->ciudad.",".$get->dir.",".$get->tel."}",$conexion); 
					$_SESSION['sucursal'] = serialize($sucursal);

				}
				elseif (!($operation['ejecution'])) {
					$log = Log::registro($usuario->getID(), "error","Registro de información. - Sucursal. {".$get->nombre.",".$get->ciudad.",".$get->dir.",".$get->tel."}",$conexion); 

				}
			}else{
				$operation['ejecution'] = true; 
				$operation['result'] = false;
				$operation['message'] = "Por favor diligencie todos los campos del formulario.";	
			}

			echo json_encode($operation);
		}

		if ($get->loadData) {

			if (isset($_SESSION['sucursal'])) {
				
				$sucursal=unserialize($_SESSION['sucursal']);
				$data['nombre']=$sucursal->getNombre();
				$ciudad = $sucursal->getCiudad();
				$data['idCiudad'] = $ciudad->getID();
				$data['nomCiudad'] = $ciudad->getNombre();
				$departamento = $ciudad->getDepartamento();
				$data['idDepartamento'] = $departamento->getID();
				$data['nomDepartamento'] = $departamento->getNombre();
				$pais = $departamento->getPais();
				$data['idPais'] = $pais->getID();
				$data['nomPais'] = $pais->getNombre();		
				$data['dir'] = $sucursal->getDireccion();
				$data['tel'] = $sucursal->getTelefono();

				$operation['ejecution'] = true;
                $operation['result'] = true;
                $operation['message'] = "Se cargo correctamente la información";
                $operation['data'] = $data;

			
			}

			echo json_encode($operation);			
		}

		if ($get->actualizar) {
		    if (isset($_SESSION['sucursal'])) {
		    	$sucursal=unserialize($_SESSION['sucursal']);

		    	if ($get->nombre != "" && $get->pais != "" && $get->departamento != "" && $get->ciudad != "" && $get->dir != "" && $get->tel != "") {
					$get->nombre =	strtoupper(base64_decode($get->nombre));
					$get->ciudad =	base64_decode($get->ciudad);
					$get->dir =	strtoupper(base64_decode($get->dir));
					$get->tel =	base64_decode($get->tel);

					$conexion = new Conexion();
					$sucursal->instanciar($sucursal->getID(), $conexion);
					$operation = $sucursal->modificar($get->nombre, $get->ciudad, $get->dir, $get->tel, $conexion);

					if (($operation['ejecution']) && ($operation['result'])) {

						$operation['message'] = "La informacion se actualizo exitosamente.";
						$log = Log::registro($usuario->getID(), "info","Actualizacion de información. - Sucursal. {".$get->nombre.",".$get->ciudad.",".$get->dir.",".$get->tel."}",$conexion); 
						$_SESSION['sucursal'] = serialize($sucursal);

					}
					elseif (!($operation['ejecution'])) {
						$log = Log::registro($usuario->getID(), "error","Actualizacion de información. - Sucursal. {".$get->nombre.",".$get->ciudad.",".$get->dir.",".$get->tel."}",$conexion); 

					}
				}else{
					$operation['ejecution'] = true; 
					$operation['result'] = false;
					$operation['message'] = "Por favor diligencie todos los campos del formulario.";	
				}

				echo json_encode($operation);
		    }
		}

		if ($get->buscar) {

			$conexion=new Conexion();

			$get->parametro = strtoupper(base64_decode($get->parametro));

			$operation = Sucursal::buscar($get->parametro, $conexion);

			if($operation['result']){
				if(count($operation['result'])==1)
	        	{
		            $operation['message'] = "Se encontro ".count($operation['result'])." registro.";
		        }
		        elseif(count($operation['result'])>1)
	        	{
		            $operation['message'] = "Se encontraron ".count($operation['result'])." registros.";
		        }
			}else{
				$operation['message'] = "No se encuentran registros con los parametros ingresados.\n";
			}
			

			echo json_encode($operation);
		}

		if ($get->instanciar) {
			$get->id_sucursal=base64_decode($get->id_sucursal);
			
			$conexion = new Conexion();
			$sucursal = new Sucursal();
			$operation = $sucursal->instanciar($get->id_sucursal, $conexion);

			$_SESSION['sucursal'] = serialize($sucursal);

			$operation['message'] = "La sucursal ". $sucursal->getNombre()." se cargo correctamente.";
			$operation['ejecution'] = true;
			$operation['result'] = true;

			echo json_encode($operation);
			
		}

		if($get->entity == "sucursal"){

			$conexion = new Conexion();
			$operation = Sucursal::buscar('', $conexion);

			if($operation['result']){
				$i=0;

                foreach($operation['result'] as $fila){
                    $result[$i]['id'] = $fila['suc_id'];
                    $result[$i]['nombre'] = $fila['suc_nombre'];
                    $i++;
                }

                $operation['result'] = $result;
			}

            echo json_encode($operation);

        }

	}

?>