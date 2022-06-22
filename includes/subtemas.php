<?php
/**
 * Description of temas
 *
 * @author Valoriza2
 */
class subtemas extends db implements crud
{
    const tabla = "subtemas";
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
    public function listarActivos($condicion) {
        return db::select("*", 
                self::tabla,
                $condicion,
                null,
                array('orden'=>'asc')
                );
    }
    public function listarPopulares($cantidad) {
        return db::select("*", 
                self::tabla,
                array('inactivo'=>0),
                null,
                array('visto'=>'desc','orden'=>'asc'),
                null,
                $cantidad);
    }
    public function ver($id) {
        return db::select("*",self::tabla,Array("id"=>$id));
    }

}
