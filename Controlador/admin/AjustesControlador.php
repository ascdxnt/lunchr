<?php
require_once './Modelo/Entidades/DiaNoLectivo.php';
require_once './Modelo/Metodos/DiaNoLectivoMetodos.php';
class AjustesControlador
{
  public function Index()
  {
    $jsonConfiguracionContra = file_get_contents('./Core/ContrasenaConfiguracion.json');
    $configs = json_decode($jsonConfiguracionContra);
    $DiaNoLectivoMetodos = new DiaNoLectivoMetodos();
    $diasNoLectivos = $DiaNoLectivoMetodos->BuscarTodos();

    if($diasNoLectivos != null){
      $arrayNoLectivos = array();
      for($i = 0; $i < sizeof($diasNoLectivos); $i++){
          $arrayNoLectivos[$i] = array("id" => $diasNoLectivos[$i]->getId());
          $arrayNoLectivos[$i] += array("nombre" => $diasNoLectivos[$i]->getNombre());
          $arrayNoLectivos[$i] += array("fecha" => $diasNoLectivos[$i]->getFecha());
          $arrayNoLectivos[$i] += array("estado" => $diasNoLectivos[$i]->getEstado());
      }
    }

    if(isset($arrayNoLectivos))
      $arrayNoLectivos = json_encode($arrayNoLectivos);
    else
      $arrayNoLectivos = null;
  	
    require_once "./Vista/views/admin/Ajustes.php";
  }
}
