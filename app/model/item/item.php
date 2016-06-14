<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/item/materia_prima.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');

class Item{

	private $id;
	private $referencia;
	private $porcentiva;
	private $unidadmedida;
	private $materiaprima;
	private $productoprocesado;
	private $productoterminado;

	public function getID()
	{
		return $this->id;
	}

	public function getReferencia()
	{
		return $this->referencia;
	}

	public function getPorcentiva()
	{
		return $this->porcentiva;
	}	

	public function getUnidadmedida()
	{
		return $this->unidadmedida;
	}

	public function getMateriaprima()
	{
		return $this->materiaprima;
	}

	public function getProductoprocesado()
	{
		return $this->productoprocesado;
	}

	public function getProductoterminado()
	{
		return $this->productoterminado;
	}
	
	public function __construct($id, $conexion)
	{
		$consulta = 'SELECT * FROM item WHERE itm_id = ?;';
		$params =  array(0 => $id);

		$operation = $conexion->select($consulta, $params);

		if ($operation['ejecution']) {
			if($operation['result']){
				foreach ($operation['result'] as $item) {
					$this->id = $item['itm_id'];
					$this->referencia = $item['itm_referencia'];
					$this->porcentiva = $item['itm_porcentiva'];
					$this->unidadmedida = $item['itm_unidadmedida'];
				}
			}
		}
	}

	public function instanciarMateriaprima($conexion)
	{
		$consulta = 'SELECT * FROM materiaprima_item WHERE mpitm_itmid = ?;';
		$params =  array(0 => $this->id);
		$operation = $conexion->select($consulta, $params);
		if ($operation['ejecution']) {
			if($operation['result']){
				foreach ($operation['result'] as $mp) {
					$this->materiaprima =new Materia_Prima($mp['mpitm_mpid'], $conexion);
				}
			}
		}
	}

	public function instanciarProductoprocesado($conexion){}

	public function instanciarProductoterminado($conexion){}

	static public function registrar($id, $referencia, $porcentiva, $unidadmedida, $tipo, $conexion)
	{
		if ($tipo == 'materia_prima') {
			$consulta = 'WITH insert1 AS (INSERT INTO item (itm_referencia, itm_porcentiva, itm_unidadmedida) VALUES (?,?,?) RETURNING itm_id) INSERT INTO materiaprima_item (mpitm_itmid, mpitm_mpid) VALUES ((SELECT itm_id FROM insert1), ?) RETURNING mpitm_itmid;';
		}else if($tipo == 'producto_terminado'){
		}else{
		}
		$params[] = array(0 => $referencia,
						1 => $porcentiva,
						2 => $unidadmedida,
						3 => $id);
		$parameters[] = array('consulta' => $consulta, 'parameter' => $params);

		return $conexion->dml($parameters);
	}

	public function modificar($referencia, $porcentiva, $unidadmedida, $tipo, $conexion)
	{
		if ($tipo == 'materia_prima') {
			$consulta = 'UPDATE item SET itm_referencia = ?, itm_porcentiva = ?, itm_unidadmedida = ? WHERE itm_id = ?';
			$instancia = 'instanciarMateriaprima';
		}else if($tipo == 'producto_terminado'){
			$instancia = 'Productoterminado';
		}else{
			$instancia = 'Productoprocesado';
		}
		$params[] = array(0 => $referencia, 
						  1 => $porcentiva,
						  2 => $unidadmedida,
						  3 => $this->id);
		$parameters[] = array('consulta' => $consulta ,'parameter' => $params);
		$operation = $conexion->dml($parameters);
		self::__construct($this->id, $conexion);
		$this->$instancia($conexion);
		
		return $operation;
	}

	static public function buscar($palabra, $tipo, $conexion)
	{
		if ($tipo == 'materia_prima') {
			$consulta = 'SELECT * FROM materiaprima_item AS mi INNER JOIN item AS i ON mi.mpitm_itmid = i.itm_id INNER JOIN materia_prima AS mp ON mi.mpitm_mpid = mp.mp_id WHERE mp.mp_nombre LIKE ?;';
		}else if($tipo == 'producto_terminado'){

		}else{

		}
		$params = array(0 => '%' . $palabra . '%');
		$operation = $conexion->select($consulta, $params);
		
		return $operation;
	}

}

/*
	//1. registrar item y materia prima he instanciarlos
	$conexion = new Conexion();
	$operation = Materia_Prima::registrar('nuevo cambio nombre materia prima', 'nuevo cambio Descripción materia prima', json_encode("[{'nombre': 'nuevo cambio nombre caracteristica', 'descripcion' : 'nuevo cambio texto descripción'}]"), $conexion);
	if($operation['ejecution'] && $operation['result']){
		$operation = Item::registrar($operation['returning']['id'], 'nuevo cambio 2', '13.5', 'KG', 'materia_prima', $conexion);
		if($operation['ejecution'] && $operation['result']){
			$item =new Item($operation['returning']['id'], $conexion);
			$item->instanciarMateriaprima($conexion);
		}
	}

	echo $item->getReferencia();
	$mp = $item->getMateriaprima();
	echo $mp->getNombre();

	//2. instanciar Item - Materia Prima
	$conexion =new Conexion();
	$item =new Item(11,$conexion);
	$item->instanciarMateriaprima($conexion);
	echo $item->getReferencia();
	$mp = $item->getMateriaprima();
	echo $mp->getNombre();

	//5. modificar item y materia prima
	$conexion =new Conexion();
	$item =new Item('11',$conexion);
	$item->instanciarMateriaprima($conexion);
	$mp = $item->getMateriaprima();
	$operation = $mp->modificar('Nombre materia prima modificada AD', 'Descripción materia prima modificada AD', json_encode("[{'nombre': 'nombre caracteristica modificada AD', 'descripcion' : 'texto descripción modificada AD'}]"), $conexion);
	if($operation['ejecution'] && $operation['result']){
		$operation = $item->modificar('Item modificado AD', '98', 'KG', 'materia_prima', $conexion);
		if ($operation['ejecution'] && $operation['result']) {
			echo $item->getReferencia();
			$mp = $item->getMateriaprima();
			echo $mp->getNombre();
		}
	}
	//6.filtro por tipo y nombre
	$conexion =new Conexion();
	$operation = Item::buscar('Nombre', 'materia_prima', $conexion);
	print_r($operation);
*/
?>