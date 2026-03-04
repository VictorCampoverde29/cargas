<?php
use App\Models\ModulosModel;

if (!function_exists('getMenuAgrupadoPorPerfil')) {
    function getMenuAgrupadoPorPerfil($idPerfil)
    {
        $modulosModel = new ModulosModel();
        $menuPlano = $modulosModel->getModulosConSubmodulosPorPerfil($idPerfil);
        $modulosAgrupados = [];
        $submodulosPorId = [];

        // Primero, agrupar todos los submodulos
        foreach ($menuPlano as $item) {
            if (!empty($item['idsubmodulos'])) {
                $submodulosPorId[$item['idsubmodulos']] = [
                    'idsubmodulos' => $item['idsubmodulos'],
                    'idpadre' => $item['idpadre'],
                    'nombre' => $item['submodulo_nombre'],
                    'icono' => $item['submodulo_icono'],
                    'ruta' => $item['submodulo_url'],
                    'submodulos' => []
                ];
            }
        }

        // Anidar los submodulos hijos
        foreach ($submodulosPorId as $id => &$submodulo) {
            if (!empty($submodulo['idpadre']) && isset($submodulosPorId[$submodulo['idpadre']])) {
                $submodulosPorId[$submodulo['idpadre']]['submodulos'][] = &$submodulo;
            }
        }
        unset($submodulo);

        // Agrupar los modulos y asignar los submodulos
        foreach ($menuPlano as $item) {
            $idmodulo = $item['idmodulos'];
            if (!isset($modulosAgrupados[$idmodulo])) {
                $modulosAgrupados[$idmodulo] = [
                    'idmodulos' => $item['idmodulos'],
                    'nombre' => $item['nombre'],
                    'icono' => $item['icono'],
                    'ruta' => $item['url'],
                    'submodulos' => []
                ];
            }
            if (!empty($item['idsubmodulos']) && empty($item['idpadre']) && isset($submodulosPorId[$item['idsubmodulos']])) {
                $modulosAgrupados[$idmodulo]['submodulos'][] = $submodulosPorId[$item['idsubmodulos']];
            }
        }
        return array_values($modulosAgrupados);
    }
}
