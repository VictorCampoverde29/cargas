<?php

namespace App\Models;

use CodeIgniter\Model;

class ModulosModel extends Model
{
  protected $table      = 'modulos';
  protected $primaryKey = 'idmodulos';
  protected $allowedFields = ['nombre', 'descripcion', 'estado', 'icono', 'url', 'orden', 'tipo', 'eliminado'];

  public function getModulosConSubmodulosPorPerfil($id_perfil)
  {
    return $this->select('modulos.*, acceso_modulo.puede_ver, acceso_modulo.puede_crear, acceso_modulo.puede_editar, acceso_modulo.puede_eliminar, submodulos.idsubmodulos, submodulos.idpadre, submodulos.nombre AS submodulo_nombre, submodulos.icono AS submodulo_icono, submodulos.url AS submodulo_url, submodulos.orden AS submodulo_orden, acceso_submodulo.puede_ver AS submodulo_puede_ver, acceso_submodulo.puede_crear AS submodulo_puede_crear, acceso_submodulo.puede_editar AS submodulo_puede_editar, acceso_submodulo.puede_eliminar AS submodulo_puede_eliminar')
      ->join('acceso_modulo', 'acceso_modulo.idmodulos = modulos.idmodulos')
      ->join('submodulos', 'submodulos.idmodulos = modulos.idmodulos AND submodulos.estado = 1 AND submodulos.eliminado = 0', 'left')
      ->join('acceso_submodulo', 'submodulos.idsubmodulos = acceso_submodulo.idsubmodulos AND acceso_submodulo.idperfil = acceso_modulo.idperfil', 'left')
      ->where('tipo', 'CA')
      ->where('acceso_modulo.idperfil', $id_perfil)
      ->where('modulos.estado', 1)
      ->where('modulos.eliminado', 0)
      ->where('acceso_modulo.puede_ver', 1)
      ->groupStart()
        ->where('acceso_submodulo.puede_ver', 1)
        ->orWhere('submodulos.idsubmodulos', null)
      ->groupEnd()
      ->orderBy('modulos.orden', 'ASC')
      ->orderBy('submodulos.orden', 'ASC')
      ->findAll();
  }

  public function getUrlXPerfil($id_perfil)
  {
    return $this->select('submodulos.nombre AS submodulo_nombre, submodulos.url AS submodulo_url')
      ->join('acceso_modulo', 'acceso_modulo.idmodulos = modulos.idmodulos')
      ->join('submodulos', 'submodulos.idmodulos = modulos.idmodulos AND submodulos.estado = 1 AND submodulos.eliminado = 0', 'left')
      ->join('acceso_submodulo', 'submodulos.idsubmodulos = acceso_submodulo.idsubmodulos AND acceso_submodulo.idperfil = acceso_modulo.idperfil', 'left')
      ->where('tipo', 'CA')
      ->where('acceso_modulo.idperfil', $id_perfil)
      ->where('modulos.estado', 1)
      ->where('modulos.eliminado', 0)
      ->where('acceso_modulo.puede_ver', 1)
      ->groupStart()
        ->where('acceso_submodulo.puede_ver', 1)
        ->orWhere('submodulos.idsubmodulos', null)
      ->groupEnd()
      ->orderBy('modulos.orden', 'ASC')
      ->orderBy('submodulos.orden', 'ASC')
      ->findAll();
  }
}