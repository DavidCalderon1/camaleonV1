<?php 

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/pucclase.php');

	class PUCGrupo extends PUC{

		private $clase;

		public function __construct($id,$conexion){
			$consulta='select * from cuenta_grupo where cntg_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){
					foreach($operation['result'] as $fila){
						$this->id = $fila['cntg_id'];
						$this->nombre = $fila['nombre'];
						$this->descripcion = $fila['descripcion'];
						$this->ajuste =$fila['ajuste'];
						$this->clase =$fila['cntg_cntcid'];
					}
				}
			}

		}

		public function cargarClase($conexion){
			$clase=$this->clase;
			$this->clase = new PUCClase($clase,$conexion); 
		}

		public function getClase(){
			return $this->clase;
		}

		public static function listar($id,$conexion){

			$consulta='select * from cuenta_grupo where cntg_cntcid=? order by cntg_id;';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);
			return $operation;

		}

	}

?>
