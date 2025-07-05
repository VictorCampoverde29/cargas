<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'idusuarios';
    protected $allowedFields = ['usuario','clave','estado','perfil','password_usu'];  

    public function usuarios_activos()
    {
        return $this->select('idusuarios,usuario')
                    ->where('estado','ACTIVO')
                    ->whereNotIn('perfil', ['CONDUCTOR'])
                    ->orderBy('usuario','ASC')
                    ->findAll();
    }
    public function getUserData($usuario)
    {
        // Obtener el usuario desde la base de datos
        $user = $this->where('idusuarios', $usuario)
                     ->where('estado', 'ACTIVO')
                     ->first();        
        return $user; 
    }

    public function getUser($usuario, $clave)
    {
        // Obtener el usuario desde la base de datos
        $user = $this->select('usuarios.idusuarios,usuarios.password_usu,usuarios.usuario,usuarios.perfil,per.nombre')
                     ->join('personal per','usuarios.idpersonal=per.idpersonal')
                     ->where('usuarios.usuario', $usuario)
                     ->where('usuarios.estado', 'ACTIVO')
                     ->first();        
    
        // Verificar si el usuario fue encontrado
        if ($user) {
           
            $passwordCheck = password_verify($clave, $user['password_usu']);            
                
            if ($passwordCheck) {
                return $user; // La contraseña es correcta
            }
        }        
        
        return null; // Usuario desactivado o contraseña incorrecta
    } 
    
    public function updatePassword($username, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($username, ['password_usu' => $hashedPassword]);
    }

    public function exists($descripcion, $id = null)
    {
        $query = $this->where('descripcion', $descripcion);
        
        // Si se proporciona un ID, excluimos ese registro
        if ($id !== null) {
            $query->where('idusuarios !=', $id);
        }

        return $query->first() !== null;
    }
}