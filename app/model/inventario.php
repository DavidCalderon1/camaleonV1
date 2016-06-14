<?php 

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/item/item.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');

	class Inventario{

		private $id;
		private $fecha;
		private $item;
		private $entradasalida;
		private $cantidad;
		private $valorunitario;
		
		public function getID(){
			return $this->id;
		}

		public function getFecha(){
			return $this->fecha;
		}
		
		public function getItem(){
			return $this->item;
		}

		public function getEntradaSalida(){
			return $this->entradasalida;
		}

		public function getCantidad(){
			return $this->cantidad;
		}

		public function getValorUnitario(){
			return $this->valorunitario;
		}

		public function __construct($id, $conexion){

			$consulta = 'SELECT * FROM inventario WHERE iv_id = ?;';
			$params =  array(0 => $id);

			$operation = $conexion->select($consulta, $params);

			if ($operation['ejecution']) {
				if($operation['result']){
					foreach ($operation['result'] as $item) {
						$this->id = $item['iv_id'];
						$this->fecha = $item['iv_fecha'];
						$this->item = $item['iv_itmid'];
						$this->entradasalida = $item['iv_entradasalida'];
						$this->cantidad = $item['iv_cantidad'];
						$this->valorunitario = $item['iv_valorunitario'];
					}

					$this->cargarItem($conexion);

				}
			}

		}

		private function cargarItem($conexion){

			$this->item = new Item($this->item, $conexion);
			$this->item->instanciarMateriaprima($conexion);

		}

		public static function registrar($fecha, $item, $entradasalida, $cantidad, $valorunitario, $conexion){
			
			$consulta = 'INSERT INTO inventario (iv_fecha, iv_itmid, iv_entradasalida, iv_cantidad, iv_valorunitario) VALUES (?,?,?,?,?) RETURNING iv_id;';
			
			$params[] = array(0 => $fecha,
							1 => $item,
							2 => $entradasalida,
							3 => $cantidad,
							4 => $valorunitario);
			$parameters[] = array('consulta' => $consulta, 'parameter' => $params);

			return $conexion->dml($parameters);

		}

		public function modificar($fecha, $item, $entradasalida, $cantidad, $valorunitario, $conexion)
		{
			
			$consulta = 'UPDATE inventario SET iv_fecha = ?, iv_itmid = ?, iv_entradasalida = ?, iv_cantidad = ?, iv_valorunitario = ? WHERE iv_id = ?';

			$params[] = array(0 => $fecha, 
							  1 => $item,
							  2 => $entradasalida,
							  3 => $cantidad,
							  4 => $valorunitario,
							  5 => $this->id);

			$parameters[] = array('consulta' => $consulta ,'parameter' => $params);
			$operation = $conexion->dml($parameters);

			self::__construct($this->id, $conexion);
			
			return $operation;
		}

		public static function buscar($item, $fecha, $conexion){

			$consulta='SELECT mp_nombre, iv_id, iv_fecha, iv_itmid, iv_entradasalida, iv_cantidad, iv_valorunitario FROM inventario INNER JOIN item ON(iv_itmid = itm_id) INNER JOIN materiaprima_item ON(itm_id = mpitm_itmid) INNER JOIN materia_prima ON(mpitm_mpid = mp_id) WHERE iv_itmid = ? AND iv_fecha = ?;';
			$parameter= array(
				0=>$item,
				1=>$fecha,
			);

			$operation = $conexion->select($consulta, $parameter);
			return $operation;

		}

		public static function reporteItem($item, $conexion){

			$consulta='SELECT mp_nombre, iv_id, iv_fecha, iv_itmid, iv_entradasalida, iv_cantidad, iv_valorunitario FROM inventario INNER JOIN item ON(iv_itmid = itm_id) INNER JOIN materiaprima_item ON(itm_id = mpitm_itmid) INNER JOIN materia_prima ON(mpitm_mpid = mp_id) WHERE iv_itmid = ?;';
			$parameter= array(
				0=>$item,
			);

			$operation = $conexion->select($consulta, $parameter);
			return $operation;

		}

	}

	//1. Registro

		//a. Previamente tiene que haberse registrado el item

			/*$conexion = new Conexion();
			$operation = Materia_Prima::registrar("Papel 1/4", "Papel de 1/4 para impresion digital.", "{{'MEDIDAS', '1/4'},{'GRAMAGE', '25mm'}}", $conexion);
			if($operation['ejecution'] && $operation['result']){
				$operation = Item::registrar($operation['returning']['id'], 'REF-P1', '4.5', 'UNIDAD', 'materia_prima', $conexion);
				if($operation['ejecution'] && $operation['result']){
					$item =new Item($operation['returning']['id'], $conexion);
					$item->instanciarMateriaprima($conexion);
				}
			}

			echo $item->getReferencia().'<br>';
			$mp = $item->getMateriaprima();
			echo $mp->getNombre().'<br>';

			$array = explode('},{', $mp->getCaracteristicas());
			print_r($array);echo '<br>';
			for($i=0; $i<count($array);$i++)
			{
				$array[$i] = trim($array[$i], '{{');$array[$i] = trim($array[$i], '{');
				$array[$i] = trim($array[$i], '}}');$array[$i] = trim($array[$i], '}');

				$array[$i] = str_getcsv($array[$i]);
			}

			print_r($array);echo '<br>';*/
		
		//b. registro en el inventario

			/*$conexion = new Conexion();
			$operation = Inventario::registrar('09/12/2015', 1, 'ENTRADA', 20, 5000, $conexion);

			print_r($operation);

			$inventario = new Inventario($operation['returning']['id'], $conexion);
			$item = $inventario->getItem();
			echo $item->getReferencia().'<br>';
			$mp = $item->getMateriaprima();
			echo $mp->getNombre().'<br>';*/

	//2. actualizar

		//a. Previamente tiene que haberse instanciado el inventario

			/*$conexion = new Conexion();

			$inventario = new Inventario(1, $conexion);
			$item = $inventario->getItem();
			echo $item->getReferencia().'<br>';
			$mp = $item->getMateriaprima();
			echo $mp->getNombre().'<br>';*/

		//b. actualizar inventario

			/*$operation = $inventario->modificar('19/01/2014', 1, 'SALIDA', 10, 10000, $conexion);
			print_r($operation);*/

	//3. buscar

		/*$conexion = new Conexion();
		$operation = Inventario::buscar(1, '19/01/2014', $conexion);
		print_r($operation);*/

	//3. reporte

		/*$conexion = new Conexion();
		$operation = Inventario::reporteItem(1, $conexion);
		print_r($operation);*/







	



?>