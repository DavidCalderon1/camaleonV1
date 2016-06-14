
<?php

	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/terceros/persona.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/terceros/empresa.php');

	class Tercero{

		private $id;
		private $tipo;
		private $regimen;
		private $gc; 
		private $empresa; 
		private $persona;

		public function getId(){
			return $this->id;
		}

		public function getTipo(){
			return $this->tipo;
		}

		public function getRegimen(){
			return $this->regimen;
		}

		public function getGC(){
			return $this->gc;
		}

		public function getEmpresa(){
			return $this->empresa;
		}

		public function getPersona(){
			return $this->persona;
		}

		public function __construct($id, $conexion){

			$consulta='select * from tercero where trc_id=?';
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['trc_id'];
						$this->tipo = $fila['trc_tipo'];
						$this->regimen = $fila['trc_regimen'];
						$this->gc = $fila['trc_gc'];
					}

					if($this->tipo=="persona"){
						$this->cargarPersona($conexion);
					}elseif($this->tipo=="empresa"){
						$this->cargarEmpresa($conexion);
					}

				}
			}

		}	

		/*public function instanciarID($id, $conexion){

			$consulta='select * from tercero where trc_id=?';	
			
			$this->cargar($consulta, $id, $conexion);

		}

		public function instanciarSubID($id, $tipo, $conexion){

			if($tipo == "persona"){
				$consulta='select * from tercero inner join tercero_persona on (trc_id=trcprn_trcid) inner join persona on (trcprn_prnid=prn_id) where prn_numdoc=?';
			}elseif($tipo == "empresa"){
				$consulta='select * from tercero inner join tercero_empresa on (trc_id=trcempr_trcid) inner join empresa on (trcempr_emprid=empr_id) where empr_nit=?';
			} 

			$this->cargar($consulta, $id, $conexion);
			
		}

		private function cargar($consulta, $id, $conexion){

			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['trc_id'];
						$this->tipo = $fila['trc_tipo'];
						$this->regimen = $fila['trc_regimen'];
						$this->gc = $fila['trc_gc'];
					}

					if($this->tipo=="persona"){
						$this->cargarPersona($conexion);
					}elseif($this->tipo=="empresa"){
						$this->cargarEmpresa($conexion);
					}

				}
			}

		}*/

		private function cargarEmpresa($conexion){

			$consulta='select empr_nit from tercero_empresa inner join empresa on (trcempr_emprid=empr_id) where trcempr_trcid=?';
			$parameter = array(0=>$this->id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->empresa = $fila['empr_nit'];
					}

					$this->empresa = new Empresa($this->empresa, $conexion);

				}
			}

		}

		private function cargarPersona($conexion){

			$consulta='select prn_numdoc from tercero_persona inner join persona on (trcprn_prnid=prn_id) where trcprn_trcid=?';
			$parameter = array(0=>$this->id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->persona = $fila['prn_numdoc'];
					}

					$this->persona = new Persona($this->persona, $conexion);

				}
			}
			
		}

		public static function registrar($id, $tipo, $regimen, $gc, $conexion){

			if($tipo == "persona"){

				$consulta='WITH insert1 AS (INSERT INTO tercero (trc_tipo, trc_regimen, trc_gc) VALUES (?, ?, ?) RETURNING trc_id) INSERT INTO tercero_persona (trcprn_trcid, trcprn_prnid) VALUES ((SELECT trc_id FROM insert1), ?) RETURNING trcprn_trcid;';			

			}elseif($tipo == "empresa"){

				$consulta='WITH insert1 AS (INSERT INTO tercero (trc_tipo, trc_regimen, trc_gc) VALUES (?, ?, ?) RETURNING trc_id) INSERT INTO tercero_empresa (trcempr_trcid, trcempr_emprid) VALUES ((SELECT trc_id FROM insert1), ?) RETURNING trcempr_trcid;';

			}
			
			$parameter[] = array(0=>$tipo,1=>$regimen,2=>$gc,3=>$id,);
			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter);

			return $conexion->dml($parameters);

		}

		public function modificar($tipo, $regimen, $gc, $conexion){

			$consulta='update tercero set trc_tipo=?, trc_regimen=?, trc_gc=? where trc_id=?;';

			$parameter[] = array(
				0=>$tipo,
				1=>$regimen,
				2=>$gc,
				3=>$this->id,
			);

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter);

			return $conexion->dml($parameters);
		
		}

		public static function buscar($parametro, $conexion){

			$result = array();

			$consulta='SELECT trc_id, trc_tipo, trc_regimen, trc_gc, empr_id, empr_nit, empr_rs, empr_naturaleza, empr_fechaconst, empr_cddid, empr_dir, empr_tel FROM tercero inner join tercero_empresa on (trc_id=trcempr_trcid) inner join empresa on (trcempr_emprid = empr_id) WHERE empr_nit || empr_rs LIKE ?;';
			$parameter= array(0=>'%'.$parametro.'%');
			
			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){
					$result = array_merge($result, $operation['result']);
				}
			}else{
				return $operation;
			}

			$consulta='SELECT trc_id, trc_tipo, trc_regimen, trc_gc, prn_id, prn_nombre, prn_apellido, prn_doc, prn_numdoc, prn_cddid, prn_dir, prn_tel FROM tercero inner join tercero_persona on (trc_id=trcprn_trcid) inner join persona on (trcprn_prnid = prn_id) WHERE prn_numdoc || prn_nombre || prn_apellido LIKE ?;';
			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){
					$result = array_merge($result, $operation['result']);
				}
			}else{
				return $operation;
			}

			$operation['ejecution'] = true;
			$operation['result'] = $result;

			return $operation;

		} 

	}

	/* CLASE TERCERO

		REGISTRO TERCERO
		1. Registrar persona o tercero, segun corresponda. Si esta operacion se ejecuta correctamente proceder
		-> $operation = Tercero::registrar('900.801.859-1', 'empresa', 'SIMPLIFICADO', 'true', $gbd);
		-> $operation = Tercero::registrar('1018429154', 'persona', 'SIMPLIFICADO', 'false', $gbd);

		INSTANCIAR EMPRESA (conociendo el id)
		$gbd = new Conexion();
		$tercero = new Tercero();
		$tercero->instanciarID(<<id>>, $gbd);
		echo $tercero->getId();
		$empresa = $tercero->getEmpresa();
		echo $empresa->getNit();

		INSTANCIAR EMPRESA (conociendo nit)
		$gbd = new Conexion();
		$tercero = new Tercero();
		$tercero->instanciarSubID('900.801.859-1', 'empresa', $gbd);
		echo $tercero->getId();
		$empresa = $tercero->getEmpresa();
		echo $empresa->getNit();

		INSTANCIAR PERSONA (conociendo el id)
		$gbd = new Conexion();
		$tercero = new Tercero();
		$tercero->instanciarID(<<id>>, $gbd);
		echo $tercero->getId();
		$persona = $tercero->getPersona();
		echo $persona->getNumDoc();

		INSTANCIAR PERSONA (conociendo el numero de documento)
		$gbd = new Conexion();
		$tercero = new Tercero();
		$tercero->instanciarSubID('1018429154', 'persona', $gbd);
		echo $tercero->getId();
		$persona = $tercero->getPersona();
		echo $persona->getNumDoc();

		MODIFICAR TERCERO
		1. Instanciar con los metodos "instanciarID" o "instanciarSubID"
		2. Modificar persona o tercero, segun corresponda. Si esta operacion se ejecuta correctamente proceder
		-> $operation = $tercero->modificar('empresa', 'COMUN', 'false', $gbd);

		BUSCAR TERCERO
		$gbd = new Conexion();
		$operation = Tercero::buscar('A',$gbd);
		print_r($operation);

	*/

?>