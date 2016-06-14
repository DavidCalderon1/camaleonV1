<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/puc.php');

	class PUCClase extends PUC{

		private $naturaleza;

		public function __construct($id, $conexion){

			$consulta='select * from cuenta_clase where cntc_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){ 
					foreach($operation['result'] as $fila){
						$this->id = $fila['cntc_id'];
						$this->nombre = $fila['nombre'];
						$this->descripcion = $fila['descripcion'];
						$this->ajuste =$fila['ajuste'];
						$this->naturaleza =$fila['cntc_naturaleza'];
					}
				}
			}

		}

		public function getNaturaleza(){
			return $this->naturaleza;
		}

		public static function listar($conexion){

			$consulta='select * from cuenta_clase order by cntc_id;';	
			$parameter = array();

			$operation = $conexion->select($consulta, $parameter);
			
			return $operation;
			
		}

	}

?>
