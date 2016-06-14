<?php

	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/tipoDocumentoContable.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/transaccion/movimientoContable.php');

	class Transaccion{

		private $id;
		private $fecha;
		private $tipodocumento;
		private $descripcion;
		private $estado;
		private $movimiento = array();

		public function getID(){
			return $this->id;
		}

		public function getFecha(){
			return $this->fecha;
		}

		public function getTipoDocumento(){
			return $this->tipodocumento;
		}

		public function getDescripcion(){
			return $this->descripcion;
		}

		public function getMovimiento(){
			return $this->movimiento;
		}

		public function __construct($id, $conexion){

			$consulta='SELECT * FROM transaccion WHERE trs_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['trs_id'];
						$this->fecha = $fila['trs_fecha'];
						$this->tipodocumento = $fila['trs_tdcid'];
						$this->descripcion = $fila['trs_descripcion'];
					}

					$this->cargarTipoDocumento($conexion);
					$this->cargarMovimientos($conexion);

				}
			}

		}

		public static function registrar($tipo, $fecha, $descripcion, $conexion){

			$consulta='INSERT INTO transaccion (trs_tdcid, trs_fecha, trs_descripcion) VALUES (?, ?, ?) RETURNING trs_id;';

			$parameter[] = array(
				0=>$tipo,
				1=>$fecha,
				2=>$descripcion,
			);

			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter,);
			$operation = $conexion->dml($parameters);
			
			return $operation;

		}

		public static function buscar($fecha, $tipodoc, $conexion){

			if(( $fecha != '' ) && ( $tipodoc != '' )){
				$consulta='SELECT trs_id, trs_tdcid, tdc_descripcion, trs_fecha, trs_descripcion FROM transaccion INNER JOIN tipodoc_contable ON(trs_tdcid = tdc_id) WHERE trs_tdcid = ? AND trs_fecha = ?';
				$parameter= array(0=>$tipodoc, 1=>$fecha);
			}elseif( $fecha != '' ){
				$consulta='SELECT trs_id, trs_tdcid, tdc_descripcion, trs_fecha, trs_descripcion FROM transaccion INNER JOIN tipodoc_contable ON(trs_tdcid = tdc_id) WHERE trs_fecha = ?';
				$parameter= array(0=>$fecha);
			}elseif( $tipodoc != '' ){
				$consulta='SELECT trs_id, trs_tdcid, tdc_descripcion, trs_fecha, trs_descripcion FROM transaccion INNER JOIN tipodoc_contable ON(trs_tdcid = tdc_id) WHERE trs_tdcid = ?';
				$parameter= array(0=>$tipodoc);
			}

			$operation = $conexion->select($consulta, $parameter);
			return $operation;

		}

		public function modificar($tipo, $fecha, $descripcion, $conexion){

			$consulta='UPDATE transaccion SET trs_tdcid=?, trs_fecha=?, trs_descripcion=? WHERE trs_id=?;';
			$parameter[] = array(
				0=>$tipo,
				1=>$fecha,
				2=>$descripcion,
				3=>$this->id,
			);

			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			$this->__construct($this->id, $conexion);

			return $operation;

		}

		public function eliminar($conexion){

			$consulta='DELETE FROM transaccion WHERE trs_id=?;';
			$parameter[] = array(
				0=>$this->id,
			);

			$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

			$operation = $conexion->dml($parameters);

			return $operation;

		}

		private function cargarMovimientos($conexion){

			$consulta='SELECT mc_id FROM movimiento_contable WHERE mc_trsid=?';	
			$parameter = array(0=>$this->id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->movimiento[] = new MovimientoContable($fila['mc_id'], $conexion);
					}

				}
			}

		}

		private function cargarTipoDocumento($conexion){

			$tipodoc = $this->tipodocumento;
			$this->tipodocumento = new TipoDocumentoContable($tipodoc, $conexion);

		}

	}

	/*

	Utilización clase Transaccion

	1. Registrar transaccion

		$gbd = new Conexion();

		//id tipo documento contable, fecha, descricion, conexion

		$operation = Transaccion::registrar(1, '04/11/2015', 'Compra de activos.', $gbd);

		if($operation['result']){
			$transaccion = new Transaccion($operation['returning']['id'],$gbd);
			echo $transaccion->getDescripcion();
			$tipodoc = $transaccion->getTipoDocumento();
			echo $tipodoc->getDescripcion();
			$movimiento = $transaccion->getMovimiento();
			print_r($movimiento);
		}

	2. buscar transaccion

		$gbd = new Conexion();
		//print_r(Transaccion::buscar('04/11/2015', 1, $gbd));
		//print_r(Transaccion::buscar('', 1, $gbd));
		//print_r(Transaccion::buscar('04/11/2015', '', $gbd));

	3. Modificar transaccion

		//a. tiene que estar instanciada la transaccion
			
			$gbd = new Conexion();
			$transaccion = new Transaccion(1, $gbd);

		//b. modificar transaccion

			print_r($transaccion->modificar(1, '05/11/2015', 'Compra de material.', $gbd));
			echo $transaccion->getDescripcion();

	3.Eliminar transaccion

		//a. tiene que estar instanciada la transaccion
			
			$gbd = new Conexion();
			$transaccion = new Transaccion(1, $gbd);

		//b. eliminar transaccion

			print_r($transaccion->eliminar($gbd));
			$transaccion = null;

	*/

?>