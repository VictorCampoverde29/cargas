<?php

namespace App\Controllers;

use App\Models\AlmacenModel;
use App\Models\BarrasPerfilModel;
use App\Models\SucursalModel;
use CodeIgniter\Controller;
use App\Models\UsuariosModel;


class LoginController extends Controller
{
    public function index()
    {
        return view('login/login.php');
    }

    public function logueo_ingreso()
    {
    
   
      $clave = $this->request->getPost('clave');
      $usuario = strtoupper($this->request->getPost('usuario'));
      log_message('error', 'Datos recibidos: ' . json_encode(['usuario' => $usuario, 'clave' => $clave]));

      try {
            $usuarioModel = new UsuariosModel();
            $barrasperfilModel=new BarrasPerfilModel();
            // Verifica el usuario y la contraseña
            $userData = $usuarioModel->getUser($usuario, $clave); // Implementa este método en tu modelo
            //log_message('error', 'Datos recibidos: ' . json_encode($userData));

            if ($userData) {
                // Si se encontró el usuario, verifica el acceso
                $url_x_perfil=$barrasperfilModel->geturlsxperfil_aside($userData['perfil']); 
                
                        // Almacena en sesión los datos necesarios
                        session()->set([
                            'ca_nombrepersonal' => $userData['nombre'],
                            'ca_nombreusuariocorto' => $userData['usuario'],          
                            'ca_usuario' => $userData['idusuarios'],
                            'ca_perfil'=>$userData['perfil'],
                            'ca_password' => $clave,                          
                            'ca_urls'=>$url_x_perfil,                    
                            'ca_is_logged'=>true
                        ]);
                        
                        return $this->response->setJSON([
                            'success' => true
                            ]);  
                } else {
                    return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'Usuario o Clave Incorrecto'
                    ]);
                }
        } catch (\Exception $e) {
            return json_encode(['error' => ['text' => $e->getMessage()]]);
        }
    }
}


?>