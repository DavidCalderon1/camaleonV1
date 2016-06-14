<?php 

	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/ciudad.php');

	class Empresa{

		private $id; 
		private $nit;
		private $razonsocial;
		private $naturaleza;
		private $fechaconst;
		private $ciudad; 
		private $direccion; 
		private $telefono;

		public function getID(){
			return $this->id;
		}

		public function getNit(){
			return $this->nit;
		}

		public function getRazonSocial(){
			return $this->razonsocial;
		}

		public function getNaturaleza()
		{
			return $this->naturaleza;
		}

		public function getFechaconst()
		{
			return $this->fechaconst;
		}

		public function getCiudad(){
			return $this->ciudad;
		}
	
		public function getDireccion(){
			return $this->direccion;
		}

		public function getTelefono(){
			return $this->telefono;
		}

		public function __construct($nit,$conexion){

			$consulta='select * from empresa where empr_nit=?';	
			$parameter = array(0=>$nit);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){

				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['empr_id'];
						$this->nit = $fila['empr_nit'];
						$this->naturaleza = $fila['empr_naturaleza'];
						$this->fechaconst = $fila['empr_fechaconst'];
						$this->razonsocial = $fila['empr_rs'];
						$this->ciudad = $fila['empr_cddid'];
						$this->direccion = $fila['empr_dir'];
						$this->telefono = $fila['empr_tel'];
					}

					$this->ciudad = new Ciudad($this->ciudad, $conexion);

				}
			}

		}

		public static function registrar($nit,$razonsocial,$naturaleza,$fechaconst,$ciudad,$direccion,$telefono,$conexion){

			$consulta='INSERT INTO empresa (empr_nit,empr_rs,empr_naturaleza,empr_fechaconst,empr_cddid,empr_dir,empr_tel) VALUES (?,?,?,?,?,?,?) RETURNING empr_id;';

			$parameter[] = array(
				0=>$nit,
				1=>$razonsocial,
				2=>$naturaleza,
				3=>$fechaconst,
				4=>$ciudad,
				5=>$direccion,
				6=>$telefono
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter,);

			$operation = $conexion->dml($parameters);
			
			return $operation;

		}

		public function modificar($nit,$razonsocial,$naturaleza,$fechaconst,$ciudad,$direccion,$telefono,$conexion){

			$consulta='update empresa set empr_nit=?, empr_rs=?, empr_naturaleza=?, empr_fechaconst=?, empr_cddid=?, empr_dir=?,empr_tel=? where empr_nit=?;';

			$parameter[] = array(
				0=>$nit,
				1=>$razonsocial,
				2=>$naturaleza,
				3=>$fechaconst,
				4=>$ciudad,
				5=>$direccion,
				6=>$telefono,
				7=>$this->nit
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			return $operation;

		}

	}

	/*
		
		CLASE EMPRESA

		REGISTRO EMPRESA
		$conexion = new Conexion();
		$operation = Empresa::registrar('900.801.859-1','NRBMICROMATCO SAS','PRIVADA','2014/12/01','11001','CALLE 100','7026742',$conexion);
		print_r($operation);

		INSTANCIAR EMPRESA
		$conexion = new Conexion();
		$empresa = new Empresa('900.801.859-1',$conexion);
		echo $empresa->getRazonSocial();
		$ciudad = $empresa->getCiudad();
		echo $ciudad->getNombre();

		MODIFICAR EMPRESA
		1. Instanciar el objeto empresa
		-> $operation = $empresa->modificar('900.801.859-2','NRBMICROMATCO SAS','PRIVADA','2015/12/01','11001','CALLE 53','7026742',$conexion);
		-> print_r($operation);

	*/

?>