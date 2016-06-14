
<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/pais.php');

	class Departamento{

		private $id;
		private $nombre;
		private $pais = array();

		public function __construct($id, $conexion){

			try{
					
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				$sentencia = $conexion->prepare('SELECT * FROM departamento WHERE dpt_id = :id;');
				$sentencia->bindParam(':id', $id);
				$sentencia->execute();

				if(($sentencia->rowCount())==1){
										
					while($fila=$sentencia->fetch()){

						$this->id = $fila['dpt_id'];
						$this->nombre = $fila['dpt_nombre'];
						$this->pais = $fila['dpt_paisid'];
								
					}
					
				}

				$conexion->commit();
				
			}catch(PDOException $e){

				$conexion->rollBack();
				//$e->getMessage();

			}

			$this->pais = new Pais($this->pais, $conexion);

		}

		public function getID(){

			return $this->id;

		}

		public function getNombre(){

			return $this->nombre;

		}

		public function getPais(){

			return $this->pais;

		}


		public static function listar($id, $conexion){

			try{
						
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				if($id != ""){

					$sentencia = $conexion->prepare('SELECT * FROM departamento WHERE dpt_paisid = :id;');
					$sentencia->bindParam(':id',$id);

				}

				$sentencia->execute();

				if(($sentencia->rowCount())>0){

					$i=0;

					while($fila=$sentencia->fetch()){

						$resultado[$i]['id'] = $fila['dpt_id'];
						$resultado[$i]['nombre'] = $fila['dpt_nombre'];
						$i++;

					}

					$operation['result'] = $resultado;

				}else{

					$operation['result'] = false;

				}

				$operation['ejecution'] = true;

				$conexion->commit();
				
			}catch(PDOException $e){

				$conexion->rollBack();
				$operation['ejecution'] = false;
				$operation['error'] = $e->getMessage();

			}

			return $operation;

		}
		
	} 


?>