<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/puccuentaauxiliar.php');

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

            if($get->subcuenta != ""  && $get->id != "" && $get->nombre != "" && $get->descripcion != "" && $get->ajuste != "" && $get->reqta != "" && $get->estado != "" ){

                $get->subcuenta = strtoupper(base64_decode($get->subcuenta));
                $get->id = strtoupper(base64_decode($get->id));
                $get->nombre = strtoupper(base64_decode($get->nombre));
                $get->descripcion = base64_decode($get->descripcion);
                $get->ajuste = base64_decode($get->ajuste);
                $get->reqta = base64_decode($get->reqta);
                $get->estado = base64_decode($get->estado);

                $gbd = new Conexion();

                $operation = PUCCuentaAuxiliar::registrar($get->nombre, $get->descripcion, $get->ajuste, $get->reqta, $get->estado, $get->subcuenta, $get->id, $gbd);

                if(($operation['ejecution'])&&($operation['result'])){

                    $operation['message'] = "Se registro correctamente la información.";

                    $log = Log::registro($usuario->getID(), "info", "Registro de información. - Cuenta Auxiliar. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->reqta.", ".$get->estado.", ".$get->subcuenta.", ".$get->id."}", $gbd);

                    $cuenta_auxiliar = new PUCCuentaAuxiliar($get->id, $gbd);
                    $cuenta_auxiliar->cargarSubcuenta($gbd);
                    $subcuenta =  $cuenta_auxiliar->getSubcuenta();
                    $subcuenta->cargarCuenta($gbd);
                    $cuenta =  $subcuenta->getCuenta();
                    $cuenta->cargarGrupo($gbd);
                    $grupo = $cuenta->getGrupo();
                    $grupo->cargarClase($gbd);

                    $_SESSION['cuenta_auxiliar'] = serialize($cuenta_auxiliar);

                }elseif(!($operation['ejecution'])){

                    $log = Log::registro($usuario->getID(), "error", "Registro de información. - Cuenta Auxiliar. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->reqta.", ".$get->estado.", ".$get->subcuenta.", ".$get->id."} - {".$operation['error']."}", $gbd);
                }

            }else{

                $operation['ejecution'] = true;
                $operation['result'] = false;
                $operation['message'] = "Por favor diligencie los todos los campos del formulario.";

            }

            echo json_encode($operation);

        }

        if($get->loadData){

            if(isset($_SESSION['cuenta_auxiliar'])){

                $cuenta_auxiliar=unserialize($_SESSION['cuenta_auxiliar']);

                $data['nombre'] = $cuenta_auxiliar->getNombre();
                $data['descripcion'] = $cuenta_auxiliar->getDescripcion();
                $data['ajuste'] = $cuenta_auxiliar->getAjuste();
                $data['reqta'] = $cuenta_auxiliar->getReqTA();
                $data['estado'] = $cuenta_auxiliar->getEstado();
                $subcuenta = $cuenta_auxiliar->getSubcuenta();
                $data['scnt_id'] = $subcuenta->getID();
                $data['scnt_nombre'] = $subcuenta->getNombre();
                $data['id'] = $cuenta_auxiliar->getID();
                
                $operation['ejecution'] = true;
                $operation['result'] = true;
                $operation['message'] = "Se cargo correctamente la información";
                $operation['data'] = $data;

                echo json_encode($operation);

            }

        }

        if($get->instanciar){

            $gbd = new Conexion();

            $cuenta_auxiliar = new PUCCuentaAuxiliar(base64_decode($get->id), $gbd);
            $cuenta_auxiliar->cargarSubcuenta($gbd);
            $subcuenta =  $cuenta_auxiliar->getSubcuenta();
            $subcuenta->cargarCuenta($gbd);
            $cuenta =  $subcuenta->getCuenta();
            $cuenta->cargarGrupo($gbd);
            $grupo = $cuenta->getGrupo();
            $grupo->cargarClase($gbd);

            $_SESSION['cuenta_auxiliar'] = serialize($cuenta_auxiliar);

            $operation['ejecution'] = true;
            $operation['result'] = true;

            echo json_encode($operation);

        }

        if($get->actualizar){

            if(isset($_SESSION['cuenta_auxiliar'])){

                $cuenta_auxiliar=unserialize($_SESSION['cuenta_auxiliar']);

                if($get->subcuenta != ""  && $get->id != "" && $get->nombre != "" && $get->descripcion != "" && $get->ajuste != "" && $get->reqta != "" && $get->estado != "" ){

                    $get->subcuenta = strtoupper(base64_decode($get->subcuenta));
                    $get->id = strtoupper(base64_decode($get->id));
                    $get->nombre = strtoupper(base64_decode($get->nombre));
                    $get->descripcion = base64_decode($get->descripcion);
                    $get->ajuste = base64_decode($get->ajuste);
                    $get->reqta = base64_decode($get->reqta);
                    $get->estado = base64_decode($get->estado);

                    $gbd = new Conexion();

                    $operation = $cuenta_auxiliar->actualizar($get->nombre, $get->descripcion, $get->ajuste, $get->reqta, $get->estado, $get->subcuenta, $get->id, $gbd);

                    if(($operation['ejecution'])&&($operation['result'])){

                        $operation['message'] = "Se modifico correctamente la información.";

                        $log = Log::registro($usuario->getID(), "info", "Modificación de información. - Cuenta Auxiliar. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->reqta.", ".$get->estado.", ".$get->subcuenta.", ".$get->id."}", $gbd);

                        $cuenta_auxiliar = new PUCCuentaAuxiliar($get->id, $gbd);
                        $cuenta_auxiliar->cargarSubcuenta($gbd);
                        $subcuenta =  $cuenta_auxiliar->getSubcuenta();
                        $subcuenta->cargarCuenta($gbd);
                        $cuenta =  $subcuenta->getCuenta();
                        $cuenta->cargarGrupo($gbd);
                        $grupo = $cuenta->getGrupo();
                        $grupo->cargarClase($gbd);

                        $_SESSION['cuenta_auxiliar'] = serialize($cuenta_auxiliar);

                    }elseif(!($operation['ejecution'])){

                        $log = Log::registro($usuario->getID(), "error", "Modificación de información. - Cuenta Auxiliar. {".$get->nombre.", ".$get->descripcion.", ".$get->ajuste.", ".$get->reqta.", ".$get->estado.", ".$get->subcuenta.", ".$get->id."} - {".$operation['error']."}", $gbd);
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