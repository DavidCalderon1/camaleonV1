
<?php

	class Pais{

		private $id;
		private $nombre;

		public function __construct($id, $conexion){

			try{
					
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				$sentencia = $conexion->prepare('SELECT * FROM pais WHERE pais_id = :id;');
				$sentencia->bindParam(':id', $id);
				$sentencia->execute();

				if(($sentencia->rowCount())==1){
										
					while($fila=$sentencia->fetch()){

						$this->id = $fila['pais_id'];
						$this->nombre = $fila['pais_nombre'];
								
					}
					
				}

				$conexion->commit();
				
			}catch(PDOException $e){

				$conexion->rollBack();
				//$e->getMessage();

			}

		}

		public function getID(){

			return $this->id;

		}

		public function getNombre(){

			return $this->nombre;

		}

		public static function listar($id, $conexion){

			try{
						
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$conexion->beginTransaction();

				if($id == null){

					$sentencia = $conexion->prepare('SELECT * FROM pais;');

				}elseif($id != ""){

					$sentencia = $conexion->prepare('SELECT * FROM pais WHERE pais_id = :id;');
					$sentencia->bindParam(':id',$id);

				}

				$sentencia->execute();

				if(($sentencia->rowCount())>0){

					$i=0;

					while($fila=$sentencia->fetch()){

						$resultado[$i]['id'] = $fila['pais_id'];
						$resultado[$i]['nombre'] = $fila['pais_nombre'];
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