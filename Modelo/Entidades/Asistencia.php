<?php

class Asistencia
{
		private $id;
		private $idEstudiante;
		private $idProfesor;
		private $fecha;
		private $estado;

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getIdEstudiante(){
			return $this->idEstudiante;
		}

		public function setIdEstudiante($idEstudiante){
			$this->idEstudiante = $idEstudiante;
		}

		public function getIdProfesor(){
			return $this->idProfesor;
		}

		public function setIdProfesor($idProfesor){
			$this->idProfesor = $idProfesor;
		}

		public function getFecha(){
			return $this->fecha;
		}

		public function setFecha($fecha){
			$this->fecha = $fecha;
		}

		public function getEstado(){
			return $this->estado;
		}

		public function setEstado($estado){
			$this->estado = $estado;
		}
}
