<?php

class FotoPerfilControlador {

	public function GenerarFotoPerfil($name, $tmp_path, $size, $type){

		if ($name == null) {
			return "./Vista/assets/profile/default.jpg";
		}

		if ( !$this->ValidarImagen($type, $size) ) {
			return false;
		}

		$extension = pathinfo($name, PATHINFO_EXTENSION);
    $directorio = $_SERVER['DOCUMENT_ROOT'].'/comedor/Vista/assets/profile/';
    $newFileName = date('dmYHis');
    move_uploaded_file($tmp_path, $directorio.$newFileName.".".$extension);
    
    $rutaFotoPerfil = "./Vista/assets/profile/".$newFileName.".".$extension;

		return $rutaFotoPerfil;
	}
	
	public function EliminarFotoPerfil($rutaFotoPerfil) {
		if ( !file_exists($rutaFotoPerfil) ) return;

		if ( substr($rutaFotoPerfil, -11) == "default.jpg" ) return;

		unlink($rutaFotoPerfil);
	}

	private function ValidarImagen($type, $size) {
		define("MB", 1000000);

		$formatosValidos = [
			"image/png",
			"image/jpg",
			"image/jpeg"
		];

		if (!in_array($type, $formatosValidos)) return false;

		if ($size >= 2 * MB) return false;

		return true;
	}

}
