<?php

	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');

	class TipoDocumentoContable {

		private $id;
		private $sigla;
		private $descripcion;

		public function getID(){
			return $this->id;
		}

		public function getSigla(){
			return $this->sigla;
		}

		public function getDescripcion(){
			return $this->descripcion;
		}

		public function __construct($id, $conexion){

			$consulta='SELECT * FROM tipodoc_contable WHERE tdc_id=?';	
			$parameter = array(0=>$id);

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					foreach($operation['result'] as $fila){
						$this->id = $fila['tdc_id'];
						$this->sigla = $fila['tdc_sigla'];
						$this->descripcion = $fila['tdc_descripcion'];
					}

				}
			}

		}

		public static function listar($conexion){

			$consulta='SELECT * FROM tipodoc_contable;';	
			$parameter = array();

			$operation = $conexion->select($consulta, $parameter);

			if($operation['ejecution']){
				if($operation['result']){

					$i = 0;

					foreach($operation['result'] as $fila){
						$resultado[$i]['id'] = $fila['tdc_id'];
						$resultado[$i]['sigla'] = $fila['tdc_sigla'];
						$resultado[$i]['descripcion'] = $fila['tdc_descripcion'];
						$i++;
					}

					$operation['result'] = $resultado;

				}
			}

			return $operation;

		} 

	}

	/*

	Utilización clase TipoDocumentoContable

	1. Instanciar clase.
		$gbd = new Conexion();
		$tipoDocumentoContable = new TipoDocumentoContable(1, $gbd);
		echo $tipoDocumentoContable->getDescripcion();
	2. Listar
		$gbd = new Conexion();
		print_r(TipoDocumentoContable::listar($gbd));

	*/

?>