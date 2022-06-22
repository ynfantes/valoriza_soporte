<?php
/**
 * Description of usuarios
 *
 * @author Valoriza2
 */
class usuarios extends db implements crud {
    const tabla = "usuarios";
    public function actualizar($id, $data) {
        return db::update(self::tabla, $data, Array("id"=>$id));
    }

    public function borrar($id) {
        return db::delete(self::tabla, Array("id"=>$id));
    }

    public function borrarTodo() {
         return db::delete(self::tabla);
    }

    public function insertar($data) {
        return db::insert(self::tabla,$data);
    }

    public function listar() {
        return db::select("*", self::tabla);
    }

    public function ver($id) {
        return db::select("*",self::tabla,Array("id"=>$id));
    }
    
    public function obtenerUsuarioPorEmail($email) {
        $registro = array();
        $r = db::select('*', self::tabla,Array('email'=>"'$email'"));
        if($r['suceed'] && count($r['data'])>0) {
            $registro = $r['data'][0];
        }
        return $registro;
    }
    
    public function login($email,$password) {
        $r = db::select("*",self::tabla,Array("email"=>"'$email'"));
        if($r['suceed'] && count($r['data'])>0) {
            if($r['data'][0]['password']== base64_encode($password)) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['usuario']    = $r['data'][0];
                $_SESSION['status']     = 'logueado';
                $r['mensaje'] = 'Usuario registrado';
            } else {
                $r['mensaje'] = 'Ha introducido un Password incorrecto';
            }
        } else {
            $r['suceed'] = false;
            $r['mensaje'] = 'Su correo electrónico no está asociado a este sitio';
        }
    unset($r['query'],$r['stats']);
        return $r;
    }
    
    public function logout() {
        
        if (isset($_SESSION['status'])) {
            unset($_SESSION['status']);
            unset($_SESSION['usuario']);
            session_unset();
            session_destroy();
            header("location:".ROOT."administracion/");
        }
    }
    
    public static function esUsuarioLogueado() {
        if (!isset($_SESSION['status']) || $_SESSION['status'] != 'logueado' || !isset($_SESSION['usuario'])) {
            header("location:".ROOT."administracion");
            die();
        }
    }
}