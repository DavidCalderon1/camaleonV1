<?php 

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/puccuenta.php'); 
 
	class PUCSubcuenta extends PUC{

		private $nativa;
		private $cuenta;

		public function __construct($id,$conexion){
		
			$consulta='select * from subcuenta where scnt_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);
			
			if($operation['ejecution']){
				if($operation['result']){
					foreach($operation['result'] as $fila){
						$this->id = $fila['scnt_id'];
						$this->nombre = $fila['nombre'];
						$this->descripcion = $fila['descripcion'];
						$this->ajuste =$fila['ajuste'];
						$this->cuenta =$fila['scnt_cntid'];
					}
				}
			}
		
		}


		public function cargarCuenta($conexion){
			$cuenta = $this->cuenta;
			$this->cuenta = new PUCCuenta($cuenta,$conexion); 
		}	


		public function getCuenta(){
			return $this->cuenta;
		}

		public static function listar($id,$conexion){

			$consulta='select * from subcuenta where scnt_cntid=? order by scnt_id;';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);
			return $operation;

		}

		public static function registrar($nombre, $descripcion, $ajuste, $cuenta, $id, $conexion){

			$consulta='insert into subcuenta values(?,?,?,false,?,?);';

			$parameter[] = array(
				0=>$nombre,
				1=>$descripcion,
				2=>$ajuste,
				3=>$cuenta,
				4=>$id
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter,);

			$operation = $conexion->dml($parameters);
			return $operation;

		}

		public function actualizar($nombre, $descripcion, $ajuste, $subcuenta, $id, $conexion){

			$consulta='update subcuenta set nombre=?, descripcion=?, ajuste=?, scnt_cntid=?, scnt_id=? where scnt_id=?;';
			$parameter[] = array(
				0=>$nombre,  
				1=>$descripcion,
				2=>$ajuste,
				3=>$subcuenta,
				4=>$id, 
				5=>$this->id,
			);

			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			return $operation;

		} 

	}
