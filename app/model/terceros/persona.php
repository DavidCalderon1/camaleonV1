<?php

	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/ciudad.php');

	class Persona{

		private $id;
		private $nombre;
		private $apellido;
		private $tipodoc;
		private $numdoc;
		private $ciudad;
		private $direccion;
		private $telefono;

		public function getID(){
			return $this->id;
		}

		public function getNombre(){
			return $this->nombre;
		}

		public function getApellido(){
			return $this->apellido;
		}

		public function getTipoDoc(){
			return $this->tipodoc;
		}

		public function getNumDoc(){
			return $this->numdoc;
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

		public function __construct($numdoc,$conexion){

			$consulta='select * from persona where prn_numdoc=?';	
			$parameter = array(0=>$numdoc);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['prn_id'];
						$this->nombre = $fila['prn_nombre'];
						$this->apellido = $fila['prn_apellido'];
						$this->tipodoc = $fila['prn_doc'];
						$this->numdoc = $fila['prn_numdoc'];
						$this->ciudad = $fila['prn_cddid'];
						$this->direccion = $fila['prn_dir'];
						$this->telefono = $fila['prn_tel'];	
					}

					$this->ciudad = new Ciudad($this->ciudad, $conexion);
				
				}

			}

		}

		public static function registrar($nombre,$apellido,$tipodoc,$numdoc,$ciudad,$direccion,$telefono,$conexion){

			$consulta='INSERT INTO persona (prn_nombre,prn_apellido,prn_doc,prn_numdoc,prn_cddid,prn_dir,prn_tel) values(?,?,?,?,?,?,?) RETURNING prn_id';

			$parameter[] = array(
				0=>$nombre,
				1=>$apellido,
				2=>$tipodoc,
				3=>$numdoc,
				4=>$ciudad,
				5=>$direccion,
				6=>$telefono
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			return $operation;

		}

		public function modificar($nombre,$apellido,$tipodoc,$numdoc,$ciudad,$direccion,$telefono,$conexion){

			$consulta='update persona set prn_nombre=?, prn_apellido=?, prn_doc=?, prn_numdoc=?,prn_cddid=?, prn_dir=?, prn_tel=? where prn_numdoc=?;';

			$parameter[] = array(
				0=>$nombre,
				1=>$apellido,
				2=>$tipodoc,
				3=>$numdoc,
				4=>$ciudad,
				5=>$direccion,
				6=>$telefono,
				7=>$this->numdoc
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			return $operation;
		}

	}

	/*
		
		CLASE PERSONA

		REGISTRO PERSONA
		$conexion = new Conexion();
		$operation = Persona::registrar('JUAN','BAYUELO','CEDULA DE CIUDADANIA','1018429154','11001','CALLE 100','2527841',$conexion);
		print_r($operation);

		INSTANCIAR PERSONA
		$conexion = new Conexion();
		$persona = new Persona('1018429154',$conexion);
		echo $persona->getNombre();
		$ciudad = $persona->getCiudad();
		echo $ciudad->getNombre();

		MODIFICAR PERSONA
		1. Instanciar el objeto persona
		-> $operation = $persona->modificar('MARIA','FERNANDA','CEDULA DE CIUDADANIA','1018429157','11001','TRANSVERSAL 34','8077841',$conexion);
		-> print_r($operation);

	*/
	

?>