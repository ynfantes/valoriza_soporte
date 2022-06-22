<?php
/**
 * Description of articulos
 *
 * @author Valoriza2
 */
class articulos extends db implements crud {
    const tabla = "articulos";
    
    public function actualizar($id, $data) {
        $id_tema = array();
        if (isset($data['id_tema'])) {
            $id_tema = $data['id_tema'];
        }
        unset($data['id_tema']);
        $r = db::update(self::tabla, $data, Array("id"=>$id));
        if ($r['suceed']) {
            if(count($id_tema)>0) {
                $this->delete('temas_articulos', array('id_articulo'=>$id));
                for ($i = 0; $i < count($id_tema); $i++) {
                    //if (!$this->existeArticuloEnTema($id,$id_tema[$i])) {
                    $this->insert("temas_articulos", Array(
                        'id_tema'       => $id_tema[$i],
                        'id_articulo'   => $id
                    ));
                    //}
                }
            }
        }
        return $r;
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
    
    public function listarArticulos($condicion='') {
//        $sql = 'select articulos.*, temas.titulo as tema '
//                . 'from articulos join temas on temas.id = articulos.id_tema '
//                . 'order by temas.id, articulos.orden';
        $sql = 'SELECT articulos.*,';
        if ($condicion!='') {
            $sql.='temas_articulos.orden,';
        }
        $sql.='(select GROUP_CONCAT(titulo SEPARATOR ", ") from temas
            join temas_articulos on temas_articulos.id_tema = temas.id
            where temas_articulos.id_articulo = articulos.id 
            GROUP by id_articulo
            ) as tema FROM `articulos` ';
        if ($condicion!='') {
            $sql.= 'join `temas_articulos` on 
                articulos.id= temas_articulos.id_articulo
                where temas_articulos.id_tema='.$condicion.' order by '
                    . 'temas_articulos.orden';
        }
        return db::query($sql);
        
    }
    
    public function obtenerArticulosPorTema($id_tema) {
        //return db::select('*',self::tabla,Array('id_tema'=>$id_tema,'inactivo'=>0));
        $consulta = 'select articulos.* from articulos '
                . 'join temas_articulos on articulos.id = temas_articulos.id_articulo '
                . 'where temas_articulos.id_tema='.$id_tema.' order by '
                . 'temas_articulos.orden';
        return db::query($consulta);
    }
    
    public function obtenerArticulosRelacionados($id_tema) {
        $consulta = 'select articulos.*,temas_articulos.id_tema from articulos '
                . 'join temas_articulos on articulos.id = temas_articulos.id_articulo '
                . 'where temas_articulos.id_tema='.$id_tema;
        return db::query($consulta);
    }
    
    public function listarPopulares($cantidad) {
        $r = db::select('*', 
                self::tabla,
                array('inactivo'=>0),
                null,
                array('visto'=>'desc','orden'=>'asc'),
                null,
                $cantidad);
        return $r;
    }
    
    public function buscarArticulosPorContenido($s) {
        //$sql = 'select * from articulos where contenido like "%'.$s.'%" or '
        //        . 'titulo like "%'.$s.'%" COLLATE utf8_bin';
        $sql = 'select * from articulos WHERE MATCH(titulo, contenido) AGAINST ("'.$s.'")';
        return db::query($sql);
    }
    
    public function  registrar($data){
        $id_tema = array();
        if (isset($data['id_tema'])) {
            $id_tema = $data['id_tema'];
        }
        unset($data['id_tema']);
        $r = $this->insertar($data);
        
        if ($r['suceed']) {
            $id = $r['insert_id'];
            for ($i = 0; $i < count($id_tema); $i++) {
                if (!$this->existeArticuloEnTema($id,$id_tema[$i])) {
                    $this->insert("temas_articulos", Array(
                        'id_tema'       => $id_tema[$i],
                        'id_articulo'   => $id
                    ));
                }
            }
        }
        return $r;
    }
    
    public function existeArticuloEnTema($id_articulo,$id_tema) {
        $r = db::select('*','temas_articulos',array(
            'id_tema'       => $id_tema,
            'id_articulo'   => $id_articulo));
        return ($r['suceed'] && count($r['data'])>0);
    }
    
    public function obtenerTemasArticulo($id){
        $temas = array();
        $r = db::select('*','temas_articulos',array('id_articulo'=>$id));
        if ($r['suceed']) {
            $temas = $r['data'];
        }
        return $temas;
    }
}
