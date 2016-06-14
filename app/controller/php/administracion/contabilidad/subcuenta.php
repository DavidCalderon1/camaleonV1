<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/pucsubcuenta.php');

    session_start();

    if(isset($_SESSION['usuario'])){
    	
    	$usuario=unserialize($_SESSION['usuario']);

    	$get = json_decode(file_get_contents('php://input'));

        if(!isset($get->registrar)){$get->registrar=false;}else{$get->registrar = base64_decode($get->registrar);}
        if(!isset($get->loadData)){$get->loadData=false;}
        // if(!isset($get->buscar)){$get->buscar=false;}
        if(!isset($get->instanciar)){$get->instanciar=false;}else{$get->instanciar = base64_decode($get->instanciar);}
        if(!isset($get->actualizar)){$get->actualizar=false;}else{$get->actualizar = base64_decode($get->actualizar);}
        // if(!isset($get->borrar)){$get->borrar=false;}

        if($get->registrar){

            if($get->cuenta != ""  && $get->id != "" && $get->nombre != "" && $get->descripcion != "" && $get->ajuste != "" ){

                $get->cuenta = strtoupper(base64_decode($get->cuenta));
                $get->id = strtoupper(base64_decode($get->id));
                $get->nombre = strtoupper(base64_decode($get->nombre));
                $get->descripcion = base64_decode($get->descripcion);
                $get->ajuste = base64_decode($get->ajuste);

                $gbd = new Conexion();

                $operation = PUCSubcuenta::registrar($get->nombre, $get->descripcion, $get->ajuste, $get->cuenta, $get->id, $gbd);

                if(($operation['ejecution'])&&($operation['result'])){

                    $operation['message'] = "Se registro correctamente la información.";

                    $log = Log::registro($usuario->getID(), "info", "Registro de información. - Subcuenta. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->cuenta.", ".$get->id."}", $gbd);

                    $subcuenta = new PUCSubcuenta($get->id, $gbd);
                    $subcuenta->cargarCuenta($gbd);
                    $cuenta =  $subcuenta->getCuenta();
                    $cuenta->cargarGrupo($gbd);
                    $grupo = $cuenta->getGrupo();
                    $grupo->cargarClase($gbd);

                    $_SESSION['subcuenta'] = serialize($subcuenta);

                }elseif(!($operation['ejecution'])){

                    $log = Log::registro($usuario->getID(), "error", "Registro de información. - Subcuenta. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->cuenta.", ".$get->id."} - {".$operation['error']."}", $gbd);
                }

            }else{

                $operation['ejecution'] = true;
                $operation['result'] = false;
                $operation['message'] = "Por favor diligencie los todos los campos del formulario.";

            }

            echo json_encode($operation);

        }

        if($get->loadData){

            if(isset($_SESSION['subcuenta'])){

                $subcuenta=unserialize($_SESSION['subcuenta']);

                $data['nombre'] = $subcuenta->getNombre();
                $data['descripcion'] = $subcuenta->getDescripcion();
                $data['ajuste'] = $subcuenta->getAjuste();
                $cuenta = $subcuenta->getCuenta();
                $data['cnt_id'] = $cuenta->getID();
                $data['cnt_nombre'] = $cuenta->getNombre();
                $data['id'] = $subcuenta->getID();
                
                $operation['ejecution'] = true;
                $operation['result'] = true;
                $operation['message'] = "Se cargo correctamente la información";
                $operation['data'] = $data;

                echo json_encode($operation);

            }

        }

        if($get->instanciar){

            $gbd = new Conexion();

            $subcuenta = new PUCSubcuenta(base64_decode($get->id), $gbd);
            $subcuenta->cargarCuenta($gbd);
            $cuenta =  $subcuenta->getCuenta();
            $cuenta->cargarGrupo($gbd);
            $grupo = $cuenta->getGrupo();
            $grupo->cargarClase($gbd);

            $_SESSION['subcuenta'] = serialize($subcuenta);

            $operation['ejecution'] = true;
            $operation['result'] = true;

            echo json_encode($operation);

        }

        if($get->actualizar){

            if(isset($_SESSION['subcuenta'])){

                $subcuenta=unserialize($_SESSION['subcuenta']);

                if($get->cuenta != ""  && $get->id != "" && $get->nombre != "" && $get->descripcion != "" && $get->ajuste != "" ){

                    $get->cuenta = strtoupper(base64_decode($get->cuenta));
                    $get->id = strtoupper(base64_decode($get->id));
                    $get->nombre = strtoupper(base64_decode($get->nombre));
                    $get->descripcion = base64_decode($get->descripcion);
                    $get->ajuste = base64_decode($get->ajuste);

                    $gbd = new Conexion();

                    $operation = $subcuenta->actualizar($get->nombre, $get->descripcion, $get->ajuste, $get->cuenta, $get->id, $gbd);

                    if(($operation['ejecution'])&&($operation['result'])){

                        $operation['message'] = "Se modifico correctamente la información.";

                        $log = Log::registro($usuario->getID(), "info", "Modificación de información. - Subcuenta. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->cuenta.", ".$get->id."}", $gbd);

                        $subcuenta = new PUCSubcuenta($get->id, $gbd);
                        $subcuenta->cargarCuenta($gbd);
                        $cuenta =  $subcuenta->getCuenta();
                        $cuenta->cargarGrupo($gbd);
                        $grupo = $cuenta->getGrupo();
                        $grupo->cargarClase($gbd);

                        $_SESSION['subcuenta'] = serialize($subcuenta);

                    }elseif(!($operation['ejecution'])){

                        $log = Log::registro($usuario->getID(), "error", "Modificación de información. - Subcuenta. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->subcuenta.", ".$get->id."} - {".$operation['error']."}", $gbd);
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