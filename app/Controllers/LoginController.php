<?php

namespace App\Controllers;

use App\Models\ModulosModel;
use CodeIgniter\Controller;
use App\Models\UsuariosModel;


class LoginController extends Controller
{
    public function index()
    {
        return view('login/login.php');
    }

    public function unauthorized()
    {
        return view('login/unauthorized');
    }

    public function salir()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('login');
    }

    public function logueo_Ingreso()
    {
        $clave = $this->request->getPost('password');
        $usuario = $this->request->getPost('usuario');

        try {
            $usuarioModel = new UsuariosModel();
            $modulosModel = new ModulosModel();

            $userData = $usuarioModel->getUser($usuario, $clave);

            if (!$userData) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'Usuario o Clave Incorrecto'
                ]);
            }

            $rutasPermitidas = $modulosModel->getUrlXPerfil($userData['idperfil']);

            session()->set([
                'ca_nombreusuariocorto' => $userData['usuario'],
                'ca_usuario' => $userData['idusuarios'],
                'ca_password' => $clave,
                'ca_perfil' => $userData['perfil'],
                'ca_idperfil' => $userData['idperfil'],
                'ca_nombrepersonal' => $userData['nombre'] ?? '',
                'ca_rutas_permitidas' => array_column($rutasPermitidas, 'submodulo_url'),
                'ca_is_logged' => true
            ]);

            return $this->response->setJSON([
                'success' => true,
                'redirect' => '/dashboard'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en el sistema: ' . $e->getMessage()
            ]);
        }
    }
}
