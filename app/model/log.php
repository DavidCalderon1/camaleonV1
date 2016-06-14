<?php

	class Log{

		public static function registro($usuario, $tipo, $descripcion, $conexion){

			$consulta = 'INSERT INTO log (log_usrid, log_fecha, log_hora, log_tipo, log_descripcion) VALUES (?, ?, ?, ?, ?);';
			$parameter[] = array( 
				0 => $usuario, 
				1 => date("Y")."/".date("m")."/".date("d"),
				2 => date("G").":".date("i").":".date("s"),
				3 => $tipo,
				4 => $descripcion,
			);
			
			$parameters[] = array( 
				'consulta' => $consulta,
				'parameter' => $parameter,
			);

			return $conexion->dml($parameters);

		}

	}

	date_default_timezone_set("America/Bogota");

?>