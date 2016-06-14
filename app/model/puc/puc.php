<?php 

	abstract class PUC {

		protected $id; 
		protected $nombre;
		protected $descripcion;
		protected $ajuste;

		public function getID(){
			return $this->id;
		}

		public function getNombre(){
			return $this->nombre;
		}

		public function getDescripcion(){
			return $this->descripcion;
		}

		public function getAjuste(){
			return $this->ajuste;
		}

	}

?>
