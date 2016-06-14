<?php
	
	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/transaccion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/puc/puccuentaauxiliar.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/terceros/tercero.php');

	class MovimientoContable{

		private $id;
		private $transaccion;
		private $sucursal;
		private $detalle;
		private $cuenta;
		private $tercero;
		private $activo;
		private $debe;
		private $haber;

		public function getID(){
			return $this->id;
		}

		public function getTrasaccion(){
			return $this->transaccion;
		}

		public function getSucursal(){
			return $this->sucursal;
		}

		public function getDetalle(){
			return $this->detalle;
		}

		public function getCuenta(){
			return $this->cuenta;
		}

		public function getTercero(){
			return $this->tercero;
		}

		public function getActivo(){
			return $this->activo;
		}

		public function getDebe(){
			return $this->debe;
		}

		public function getHaber(){
			return $this->haber;
		}

		public function __construct($id, $conexion){

			$consulta='SELECT * FROM movimiento_contable WHERE mc_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['mc_id'];
						$this->transaccion = $fila['mc_trsid'];
						$this->sucursal = $fila['mc_sucid'];
						$this->detalle = $fila['mc_detalle'];
						$this->cuenta = $fila['mc_cntauxid'];
						$this->debe = $fila['mc_debe'];
						$this->haber = $fila['mc_haber'];
					}

					$this->cargarCuenta($conexion);
					$this->cargarTercero($conexion); 
					//$this->cargarActivo($conexion);
					
				}
			}

		}

		public function listar($transaccion, $conexion){

		}

		public static function registrar($transaccion, $sucursal, $detalle, $cuentaaux, $asociado, $activo, $debe, $haber, $conexion){

			$consulta='SELECT reqta FROM cuenta_auxiliar WHERE cntaux_id = ?';
			$parameter = array(0=>$cuentaaux,);
			$operation = $conexion->select($consulta, $parameter);

			$parameter = null;

			if($operation['result'][0][0]){
				if($asociado){
					$consulta="WITH ins1 AS (INSERT INTO movimiento_contable (mc_trsid, mc_sucid, mc_detalle, mc_cntauxid, mc_debe, mc_haber) VALUES (?, ?, ?, ?, ?, ?) RETURNING mc_id)INSERT INTO movimientocontable_tercero VALUES ((SELECT mc_id FROM ins1), ?) RETURNING mctrc_mcid;";
					$parameter[] = array(0=>$transaccion,1=>$sucursal,2=>$detalle,3=>$cuentaaux,4=>$debe,5=>$haber,6=>$asociado);
				}elseif($activo){
					$consulta="WITH ins1 AS (INSERT INTO movimiento_contable (mc_trsid, mc_sucid, mc_detalle, mc_cntauxid, mc_debe, mc_haber) VALUES (?, ?, ?, ?, ?, ?) RETURNING mc_id)INSERT INTO movimientocontable_activo VALUES ((SELECT mc_id FROM ins1), ?) RETURNING mctrc_mcid;";
					$parameter[] = array(0=>$transaccion,1=>$sucursal,2=>$detalle,3=>$cuentaaux,4=>$debe,5=>$haber,6=>$activo);

				}else{
					$operation['ejecution'] = true;
					$operation['result'] = false;
					$operation['message'] = 'Hace falta el activo o tercero.';
					return $operation;
				}
			}else{

				$consulta="INSERT INTO movimiento_contable (mc_trsid, mc_sucid, mc_detalle, mc_cntauxid, mc_debe, mc_haber) VALUES (?, ?, ?, ?, ?, ?) RETURNING mc_id;";

				$parameter[] = array(0=>$transaccion,1=>$sucursal,2=>$detalle,3=>$cuentaaux,4=>$debe,5=>$haber,);

			}

			$parameters[] = array( 'consulta' => $consulta,'parameter' => $parameter,);

			$operation = $conexion->dml($parameters);
			
			return $operation;

		}

		public function modificar($transaccion, $sucursal, $detalle, $cuentaaux, $debe, $haber, $conexion){

			$consulta='UPDATE movimiento_contable SET mc_trsid=?, mc_sucid=?, mc_detalle=?, mc_cntauxid=?, mc_debe=?, mc_haber=? WHERE mc_id=?;';
			$parameter[] = array(
				0=>$transaccion,
				1=>$sucursal,
				2=>$detalle,
				3=>$cuentaaux,
				4=>$debe,
				5=>$haber,
				6=>$this->id,
			);

			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			if($operation['result']){
				$this->__construct($this->id, $conexion);
			}

			return $operation;

		}

		public function modificarTercero($tercero,$conexion){

			if($this->cuenta->getReqTA()){// incluir activo

				$consulta='UPDATE movimientocontable_tercero SET mctrc_trcid=? WHERE mctrc_mcid=?;';
				$parameter[] = array(
					0=>$tercero,
					1=>$this->id,
				);

				$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

				$operation = $conexion->dml($parameters);

				if($operation['result']){
					$this->cargarTercero($conexion);
				}

				return $operation;
			}

		}

		public function eliminar($conexion){

			$operation_a = $this->eliminarTercero($conexion);

			//$operation_b = $this->eliminarActivo($conexion);

			if($operation_a['result'] /*|| $operation_b['result']*/){

				$consulta='DELETE FROM movimiento_contable WHERE mc_id=?;';
				$parameter[] = array(0=>$this->id,);
				$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);
				$operation = $conexion->dml($parameters);

			}

			return $operation;

		}

		private function eliminarTercero($conexion){

			$consulta='DELETE FROM movimientocontable_tercero WHERE mctrc_mcid=?;';
			$parameter[] = array(0=>$this->id,);
			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);
			$operation = $conexion->dml($parameters);

			return $operation;

		}

		private function eliminarActivo($conexion){
			
		}

		private function cargarCuenta($conexion){

			$cuenta = $this->cuenta;
			$this->cuenta = new PUCCuentaAuxiliar($cuenta, $conexion);

		}

		private function cargarTercero($conexion){

			if($this->cuenta->getReqTA()){ // incluir activo

				$consulta='SELECT mctrc_trcid FROM movimiento_contable INNER JOIN movimientocontable_tercero ON (mc_id=mctrc_mcid) WHERE mc_id=?';	
				$parameter = array(0=>$this->id);

				$operation = $conexion->select($consulta, $parameter);

				if($operation['ejecution']){
					if($operation['result']){
						foreach($operation['result'] as $fila){
							$this->tercero = $fila['mctrc_trcid'];
						}
						if($this->tercero!=''){
							$tercero = $this->tercero;
							$this->tercero = new Tercero();
							$this->tercero->instanciarID($tercero, $conexion);
						}
					}
				}

			}

		}

		private function cargarActivo($conexion){}

	}

	/*

	Utilización clase Movimiento 

	1. Registrar movimiento contable

		$gbd = new Conexion();

		//

		$operation = MovimientoContable::registrar(2,1,'Prueba de movimiento contable.', '11200501', 1, 0, 5000, 0, $gbd);

		if($operation['result']){
			$movimiento = new MovimientoContable($operation['returning']['id'], $gbd);
		}

		echo $movimiento->getDetalle();
		$cuenta = $movimiento->getCuenta();
		echo $cuenta->getNombre();
		$tercero = $movimiento->getTercero();
		echo $tercero->getTipo();
		$persona = $tercero->getPersona();
		echo $persona->getNombre();

	2. Modificar movimiento contable

		//a. tiene que estar instanciada el movimiento
			$gbd = new Conexion();
			$movimiento = new MovimientoContable(1, $gbd);

		//b. modificar movimiento
			
		$operation = $movimiento->modificar(3,1,'Prueba de movimiento contable actualizado.', '11200501', 0, 5000, $gbd);
		if($operation['result']){
			$movimiento->modificarTercero(2,$gbd);
		}
		echo $movimiento->getDetalle();
		$cuenta = $movimiento->getCuenta();
		echo $cuenta->getNombre();
		$tercero = $movimiento->getTercero();
		echo $tercero->getTipo();
		$empresa = $tercero->getEmpresa();
		echo $empresa->getRazonSocial();

	3. Eliminar movimiento contable

		//a. tiene que estar instanciada el movimiento
			
			$gbd = new Conexion();
			$movimiento = new MovimientoContable(3, $gbd);

		//b. eliminar movimiento

			print_r($movimiento->eliminar($gbd));
			$movimiento = null;

	*/

?>