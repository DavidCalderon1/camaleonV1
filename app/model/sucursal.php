<?php

//require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/ciudad.php');

class Sucursal{

	private $id;
	private $nombre;
	private $ciudad;
	private $direccion;
	private $telefono;

	public function getID(){
		return $this->id;
	}

	public function getNombre(){
		return $this->nombre;
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

	public function registrar($nombre, $ciudad, $direccion, $telefono, $conexion){

		$consulta='INSERT INTO sucursal (suc_nombre, suc_cddid, suc_dir, suc_tel) VALUES ( ?, ?, ?, ?) RETURNING suc_id;';

		$parameter[] = array(
			0=>$nombre,
			1=>$ciudad,
			2=>$direccion,
			3=>$telefono,
		);

		$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter,);
		$operation = $conexion->dml($parameters);

		if($operation['result']){
			$this->instanciar($operation['returning']['id'], $conexion);
			$operation['result'] = true;
		}
		
		return $operation;

	}

	public function instanciar($id, $conexion){

		$consulta='SELECT * FROM sucursal WHERE suc_id=?';	
		$parameter = array(0=>$id);

		$operation = $conexion->select($consulta, $parameter);

		if($operation['ejecution']){
			if($operation['result']){

				foreach($operation['result'] as $fila){
					$this->id = $fila['suc_id'];
					$this->nombre = $fila['suc_nombre'];
					$this->ciudad = $fila['suc_cddid'];
					$this->direccion =$fila['suc_dir'];
					$this->telefono =$fila['suc_tel'];
				}

				$this->ciudad = new Ciudad($this->ciudad, $conexion);

			}
		}

	}

	public function modificar($nombre, $ciudad, $direccion, $telefono, $conexion){

		$consulta='UPDATE sucursal SET suc_nombre=?, suc_cddid=?, suc_dir=?, suc_tel=? WHERE suc_id=?;';
		$parameter[] = array(
			0=>$nombre,
			1=>$ciudad,
			2=>$direccion,
			3=>$telefono,
			4=>$this->id,
		);

		$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

		$operation = $conexion->dml($parameters);

		$this->instanciar($this->id, $conexion);

		return $operation;

	}

	public static function buscar($parametro, $conexion){

		$consulta='SELECT suc_id, suc_nombre, suc_cddid, cdd_nombre, suc_dir, suc_tel FROM sucursal INNER JOIN ciudad ON(suc_cddid = cdd_id) WHERE suc_nombre || cdd_nombre LIKE ?;';
		$parameter= array(0=>'%'.$parametro.'%');

		$operation = $conexion->select($consulta, $parameter);
		return $operation;

	}

	public function eliminar($conexion){

		$consulta='DELETE FROM sucursal WHERE suc_id = ?;';
		$parameter[] = array(
			0=>$this->id,
		);

		$parameters[] = array('consulta' => $consulta, 'parameter' => $parameter);

		$operation = $conexion->dml($parameters);

		return $operation;

	}

}

/*

1.  REGISTRO SUCURSAL
	$conexion = new Conexion();
	$sucursal = new Sucursal();
	$operation = $sucursal->registrar('PRINCIPAL', 11001, 'CALLE 100 NUMERO 25 - 50', '2526735',$conexion);
	print_r($operation);
	echo $sucursal->getID();
	echo $sucursal->getDireccion();
	$ciudad = $sucursal->getCiudad();
	echo $ciudad->getNombre();

2.	INSTANCIAR SUCURSAL
	PRIMER METODO
	- Despues de un registro la clase automaticamente queda instanciada
	SEGUNDO METODO
	$conexion = new Conexion();
	$sucursal = new Sucursal();
	$sucursal->instanciar(1, $conexion);
	echo $sucursal->getID();
	echo $sucursal->getDireccion();
	$ciudad = $sucursal->getCiudad();
	echo $ciudad->getNombre();

3.	MODIFICAR SUCURSAL
	3.1
	- Haber instanciado la sucursal por registro o como este caso por su metodo instanciar
	$conexion = new Conexion();
	$sucursal = new Sucursal();
	$sucursal->instanciar(1, $conexion);
	5.2
	$operation = $sucursal->modificar('ALAMOS', 11001, 'CALLE 68 NUMERO 90 - 50', '2526735',$conexion);
	echo $sucursal->getID();
	echo $sucursal->getDireccion();
	$ciudad = $sucursal->getCiudad();
	echo $ciudad->getNombre();

4.	BUSCAR SUCURSAL
	$conexion = new Conexion();
	$operation = Sucursal::buscar("B", $conexion);
	print_r($operation);

5.	ELIMINAR SUCURSAL
	5.1
	- Haber instanciado la sucursal por registro o como este caso por su metodo instanciar
	$conexion = new Conexion();
	$sucursal = new Sucursal();
	$sucursal->instanciar(1, $conexion);
	5.2
	$operation = $sucursal->eliminar($conexion);
	print_r($operation);
	$sucursal = null;

*/


?>