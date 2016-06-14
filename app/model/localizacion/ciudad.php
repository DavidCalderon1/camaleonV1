
<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/app/model/localizacion/departamento.php');

	class Ciudad{

		private $id;
		private $nombre;
		private $departamento = array();

		public function __construct($id, $conexion){

			try{
					
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				$sentencia = $conexion->prepare('SELECT * FROM ciudad WHERE cdd_id = :id;');
				$sentencia->bindParam(':id', $id);
				$sentencia->execute();

				if(($sentencia->rowCount())==1){
										
					while($fila=$sentencia->fetch()){

						$this->id = $fila['cdd_id'];
						$this->nombre = $fila['cdd_nombre'];
						$this->departamento = $fila['cdd_dptid'];
								
					}
					
				}

				$conexion->commit();
				
			}catch(PDOException $e){

				$conexion->rollBack();
				//$e->getMessage();

			}

			$this->departamento = new Departamento($this->departamento, $conexion);

		}

		public function getID(){

			return $this->id;

		}

		public function getNombre(){

			return $this->nombre;

		}

		public function getDepartamento(){

			return $this->departamento;

		}

		public static function listar($id, $conexion){

			try{
						
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				if($id != ""){

					$sentencia = $conexion->prepare('SELECT * FROM ciudad WHERE cdd_dptid = :id;');
					$sentencia->bindParam(':id',$id);

				}

				$sentencia->execute();

				if(($sentencia->rowCount())>0){

					$i=0;

					while($fila=$sentencia->fetch()){

						$resultado[$i]['id'] = $fila['cdd_id'];
						$resultado[$i]['nombre'] = $fila['cdd_nombre'];
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