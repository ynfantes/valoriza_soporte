<?php
include_once '../../includes/constants.php';
$accion = isset($_GET['accion'])? $_GET['accion'] : '';
$usuarios = new usuarios();
$usuarios->esUsuarioLogueado();
switch ($accion) {
    
    // <editor-fold defaultstate="collapsed" desc="guardar">
    case 'guardar':
        $result = Array();
        $data = Array();
        if (strlen($_POST['contenido']) < 15) {
            $result['suceed'] = false;
            $result['mensaje'] = "Debe agregar el contenido al artículo.";
        } else {
            $articulo = new articulos();
            $data = $_POST;
            if (!isset($data['inactivo'])) {
                $data['inactivo'] = 0;
            }
            $data['fecha'] = date("Y-m-d H:i:00 ", time());
            if ($data['id']) {
                $result = $articulo->actualizar($data['id'], $data);
            } else {
                unset($data['id']);
                $result = $articulo->registrar($data);
            }
            unset($result['query']);
            if ($result['suceed']) {
                $result['suceed'] = true;
                $result['mensaje'] = '<i class="fi fi-emoji-smile"></i> Artículo guardado con éxito';
            } else {


                $result['suceed'] = false;
                $result['mensaje'] = '<i class="fi fi-emoji-shocked"></i> Ocurrió un error durante el proceso';
            }
        }
        echo json_encode($result);
        break; 
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="listar">
    case 'listar':
    default:
        $articulos = new articulos();
        $lista = array();
        $r = $articulos->listarArticulos();


        if ($r['suceed'] && count($r['data']) > 0) {
            $lista = $r['data'];
        }
        echo $twig->render('/administracion/lista.articulos.html.twig', array('lista' => $lista));
        break;
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="ordenar">
    case 'ordenar':
        $articulos = new articulos();
        $temas = new temas();
        $lista = array();
        $categ = array();
        if (isset($_POST['accion'])) {
            $temas->ordendarArticuloTema($_POST['id_tema'], $_POST['id'],$_POST['orden']);
        }
        
        $r = $temas->listarActivos(Array('inactivo'=>0));
        if ($r['suceed'] && count($r['data']) > 0) {
            $categ = $r['data'];
            $item = $categ[0]['id'];
        }
        if (isset($_GET['id'])) {
            $item = filter_input(INPUT_GET, 'id');
        }
        $r = $articulos->listarArticulos($item);
        if ($r['suceed'] && count($r['data']) > 0) {
            $lista = $r['data'];
        }
        echo $twig->render('/administracion/ordena.articulos.html.twig', array(
            'lista' => $lista,
            'temas' => $categ,
            'selec' => $item
                ));
        break;
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="leer">
    case 'leer':
        $articulos = new articulos();
        $articulo = $articulos->ver($_GET['id']);
        $titulo = 'No se encuenta el artículo';
        $contenido = '';
        if ($articulo['suceed'] && count($articulo['data']) > 0) {
            $titulo = $articulo['data'][0]['titulo'];
            $contenido = $articulo['data'][0]['contenido'];
        }
        $encabezado = '<div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">' . $titulo . '</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span class="fi fi-close fs--18" aria-hidden="true"></span>
        </button></div>';
        $cuerpo = '<div class="modal-body">' . $contenido . '</div>';
        $pie = '<div class="modal-footer"><button type="button" 
            class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>';
        $modal = $encabezado . $cuerpo . $pie;
        echo $modal;
        break;
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="editar">
    case 'editar':
        $id = $_GET['id'];
        $articulos = new articulos();
        $temas = new temas();
        $lista_temas = array();
        $articulo = array();
        $r = $articulos->ver($id);
        if ($r['suceed'] && count($r['data']) > 0) {
            $articulo = $r['data'][0];
            $articulo['temas'] = $articulos->obtenerTemasArticulo($id);
        }
        $r = $temas->listarActivos(array('inactivo' => 0));
        if ($r['suceed'] && count($r['data']) > 0) {
            $lista_temas = $r['data'];
        }
        echo $twig->render('/administracion/index.html.twig',                 array(
            'articulo' => $articulo,
            'temas' => $lista_temas,
            'accion' => 'editar'
        ));
        break;
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="escribir">
    case 'escribir' :
        $temas = new temas();
        $lista_temas = array();
        $r = $temas->listarActivos(array('inactivo' => 0));
        if ($r['suceed'] && count($r['data']) > 0) {
            $lista_temas = $r['data'];
        }
        echo $twig->render('/administracion/index.html.twig', array('temas' => $lista_temas));
        break;
    // </editor-fold>

}