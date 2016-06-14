<?php 

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/pucgrupo.php');

	class PUCCuenta extends PUC{

		private $grupo;	

		public function __construct($id,$conexion){

			$consulta='select * from cuenta where cnt_id=?';
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){
					foreach($operation['result'] as $fila){
						$this->id = $fila['cnt_id'];
						$this->nombre = $fila['nombre'];
						$this->descripcion = $fila['descripcion'];
						$this->ajuste =$fila['ajuste'];
						$this->grupo =$fila['cnt_cntgid'];
					}
				}
			}

		}


		public function cargarGrupo($conexion){
			$grupo=$this->grupo;
			$this->grupo = new PUCGrupo($grupo,$conexion); 
		}

		public function getGrupo(){	
			return $this->grupo;
		}

		public static function listar($id,$conexion){

			$consulta='select * from cuenta where cnt_cntgid=? order by cnt_id;';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);
			
			return $operation;

		}

	}
	
?>