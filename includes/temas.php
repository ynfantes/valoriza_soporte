<?php
/**
 * Description of temas
 *
 * @author Valoriza2
 */
class temas extends db implements crud
{
    const tabla = "temas";
    //put your code here
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
        return db::select("*", self::tabla,$condicion,null,array('orden'=>'asc'));
    }

    public function ver($id) {
        return db::select("*",self::tabla,Array("id"=>$id));
    }
    
    public function ordendarArticuloTema($id,$articulo,$orden) {
        $this->exec_query("START TRANSACTION");
        try {
            $this->delete("temas_articulos", array('id_tema'=>$id));
            for ($i = 0; $i < count($articulo); $i++) {
                $r = $this->insert("temas_articulos", Array(
                    "id_tema"       => $id,
                    "id_articulo"   => $articulo[$i],
                    "orden"         => $orden[$i]
                ));
            }
            $this->exec_query("COMMIT");
        } catch (Exception $exc) {
            //$resultado['suceed'] = false;
            $this->exec_query("ROLLBACK");
            echo $exc->getTraceAsString();
        }
    }
}
