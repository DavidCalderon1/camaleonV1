<?php

class Materia_Prima
{
	private $id;
	private $nombre;
	private $descripcion;
	private $caracteristicas;

	public function getID()
	{
		return $this->id;
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function getDescripcion()
	{
		return $this->descripcion;
	}

	public function getCaracteristicas()
	{
		return $this->caracteristicas;
	}
	
	public function __construct($id, $conexion)
	{
		$consulta = 'SELECT * FROM materia_prima WHERE mp_id = ?;';
		$params = array(0 => $id);
		$operation = $conexion->select($consulta, $params);

		if ($operation['ejecution']) {
			if($operation['result']){
				foreach ($operation['result'] as $mp) {
					$this->id = $mp['mp_id'];
					$this->nombre = $mp['mp_nombre'];
					$this->descripcion = $mp['mp_descripcion'];
					$this->caracteristicas = $mp['mp_caracteristicas'];
				}
			}
		}
	}

	static public function registrar($nombre, $descripcion, $caracteristicas, $conexion)
	{
		$consulta = 'INSERT INTO materia_prima (mp_nombre, mp_descripcion, mp_caracteristicas) VALUES (?,?,?) RETURNING mp_id;';
		$params[] = array(0 => $nombre, 
						1 => $descripcion,
						2 => $caracteristicas);

		$parameters[] = array('consulta' => $consulta, 'parameter' => $params);

		return $conexion->dml($parameters);;
	}

	public function modificar($nombre, $descripcion, $caracteristicas, $conexion)
	{
		$consulta = 'UPDATE materia_prima SET mp_nombre=?, mp_descripcion=?, mp_caracteristicas=? WHERE mp_id=?;';
		$params[] = array(0 => $nombre, 
						1 => $descripcion,
						2 => $caracteristicas,
						3 => $this->id);

		$parameters[] = array('consulta' => $consulta, 'parameter' => $params);

		$operation = $conexion->dml($parameters);
		self::__construct($this->id,$conexion);
		
		return $operation;
	}

	static public function listar($conexion)
	{
		$consulta = 'SELECT * FROM materia_prima';
		$params = array();
		$operation = $conexion->select($consulta, $params);

		return $operation;
	}

}

/*
	//1. Instanciar materia prima
	$conexion =new Conexion();
	$mp =new Materia_Prima(1,$conexion);

	//2. Registrar materia prima he instaciarla
	$conexion = new Conexion();
	$operation = Materia_Prima::registrar('Nombre materia prima', 'Descripción materia prima', json_encode("[{'nombre': 'nombre caracteristica', 'descripcion' : 'texto descripción'}]"), $conexion);
	if($operation['ejecution'] && $operation['result']){
		$mp =new Materia_Prima($operation['returning']['id'], $conexion);
	}

	echo $mp->getNombre();

	//4. modificar materia prima
	$conexion =new Conexion();
	$mp =new Materia_Prima(1,$conexion);
	$operation = $mp->modificar('Nombre materia prima modificada', 'Descripción materia prima modificada', json_encode("[{'nombre': 'nombre caracteristica modificada', 'descripcion' : 'texto descripción modificada'}]"), $conexion);
	if ($operation['ejecution'] && $operation['result']) {
		echo $mp->getNombre();
	}

	//5. listar materia prima
	$conexion =new Conexion();
	$operation = Materia_Prima::listar($conexion);
	print_r($operation);
*/

?>