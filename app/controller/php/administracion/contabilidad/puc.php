<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/log.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/usuario.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/puccuentaauxiliar.php');

    session_start();

    if(isset($_SESSION['usuario'])){
        
        $usuario=unserialize($_SESSION['usuario']);

        $get = json_decode(file_get_contents('php://input'));

        if($get->entity == "clase"){

            $gbd = new Conexion();

            $operation = PUCClase::listar($gbd);

            if($operation['ejecution']){
                if($operation['result']){ 
                    $i=0;
                    foreach($operation['result'] as $fila){

                        $resultado[$i]['id'] = $fila['cntc_id'];
                        $resultado[$i]['nombre'] = $fila['nombre'];
                        $resultado[$i]['descripcion'] = $fila['descripcion'];
                        $resultado[$i]['nativa'] = true;

                        $suboperation = PUCGrupo::listar($resultado[$i]['id'],$gbd);
                        if($suboperation['result']){$resultado[$i]['subentity'] = "grupo";}

                        $i++;

                    }
                    $operation['result'] = $resultado;
                }
            }

            echo json_encode($operation);

        }

        if($get->entity == "grupo"){

            $gbd = new Conexion();

            $operation = PUCGrupo::listar($get->id, $gbd);

            if($operation['ejecution']){
                if($operation['result']){ 
                    $i=0;
                    foreach($operation['result'] as $fila){

                        $resultado[$i]['id'] = $fila['cntg_id'];
                        $resultado[$i]['nombre'] = $fila['nombre'];
                        $resultado[$i]['descripcion'] = $fila['descripcion'];
                        $resultado[$i]['nativa'] = true;

                        $suboperation = PUCCuenta::listar($resultado[$i]['id'],$gbd);
                        if($suboperation['result']){$resultado[$i]['subentity'] = "cuenta";}

                        $i++;
                        
                    }
                    $operation['result'] = $resultado;
                }
            }

            echo json_encode($operation);

        }

        if($get->entity == "cuenta"){

            $gbd = new Conexion();

            $operation = PUCCuenta::listar($get->id, $gbd);

            if($operation['ejecution']){
                if($operation['result']){ 
                    $i=0;
                    foreach($operation['result'] as $fila){

                        $resultado[$i]['id'] = $fila['cnt_id'];
                        $resultado[$i]['nombre'] = $fila['nombre'];
                        $resultado[$i]['descripcion'] = $fila['descripcion'];
                        $resultado[$i]['nativa'] = true;

                        $suboperation = PUCSubcuenta::listar($resultado[$i]['id'],$gbd);
                        if($suboperation['result']){$resultado[$i]['subentity'] = "subcuenta";}

                        $i++;
                        
                    }
                    $operation['result'] = $resultado;
                }
            }

            echo json_encode($operation);

        }

        if($get->entity == "subcuenta"){

            $gbd = new Conexion();

            $operation = PUCSubcuenta::listar($get->id, $gbd);

            if($operation['ejecution']){
                if($operation['result']){ 
                    $i=0;
                    foreach($operation['result'] as $fila){

                        $resultado[$i]['id'] = $fila['scnt_id'];
                        $resultado[$i]['nombre'] = $fila['nombre'];
                        $resultado[$i]['descripcion'] = $fila['descripcion'];
                        $resultado[$i]['nativa'] = $fila['scnt_nativa'];
                        
                        if(!($resultado[$i]['nativa'])){$resultado[$i]['tipo']="subcuenta";}
                        
                        $suboperation = PUCCuentaAuxiliar::listar($resultado[$i]['id'],$gbd);

                        if($suboperation['result']){$resultado[$i]['subentity'] = "cuenta_auxiliar";}

                        $i++;
                        
                    }
                    $operation['result'] = $resultado;
                }
            }

            echo json_encode($operation);

        }

        if($get->entity == "cuenta_auxiliar"){

            $gbd = new Conexion();

            $operation = PUCCuentaAuxiliar::listar($get->id, $gbd);

            if($operation['ejecution']){
                if($operation['result']){ 
                    $i=0;
                    foreach($operation['result'] as $fila){

                        $resultado[$i]['id'] = $fila['cntaux_id'];
                        $resultado[$i]['nombre'] = $fila['nombre'];
                        $resultado[$i]['descripcion'] = $fila['descripcion'];
                        $resultado[$i]['nativa'] = false;

                        if(!($resultado[$i]['nativa'])){$resultado[$i]['tipo']="cuenta_auxiliar";}

                        $i++;
                        
                    }
                    $operation['result'] = $resultado;
                }
            }

            echo json_encode($operation);

        }

    }

?>