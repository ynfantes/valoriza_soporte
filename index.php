<?php
require_once 'includes/constants.php';

if (!isset($_GET['item'])) {
    
    $articulos = new articulos();
    $temas = new temas();
    $populares = array();
    $menu = $temas->listarActivos(array('inactivo'=>0));
    if($menu['suceed'] && count($menu['data'])>0) {    
        for($i=0; $i< count($menu['data']); $i++) {
            $articulo = $articulos->obtenerArticulosPorTema($menu['data'][$i]['id']);
            if ($articulo['suceed'] && count($articulo['data'])>0) {
                $menu['data'][$i]['submenu'] = $articulo['data'];
            } else {
                $menu['data'][$i]['submenu'] = null;
            }
        }
    }
    $r = $articulos->listarPopulares(6);
    if($r['suceed'] && count($r['data'])>0) {
        $populares = $r['data'];
    }
    echo $twig->render('index.html.twig',array(
        'menu'  => $menu['data'],
        'popu'  => $populares
    ));
} else {
    $id         = filter_input(INPUT_GET, 'item');
    $id_tema    = filter_input(INPUT_GET, 'topic');
    $articulos  = new articulos();
    
    $articulo   = array();
    $listado    = array();
    $r = $articulos->ver($id);
    
    if ($r['suceed'] && count($r['data'])>0) {
        $articulo = $r['data'][0];
        $articulos->actualizar($articulo['id'], array('visto'=>$articulo['visto']+1));
    }
    if (count($articulo)>0) {
        if ($id_tema=='') {
            $r = $articulos->listarpopulares(10);
        } else {
            $r = $articulos->obtenerArticulosRelacionados($id_tema);
        }
        if ($r['suceed'] && count($r['data'])>0) {
            $listado = $r['data'];
        }
    }
    echo $twig->render('tema.html.twig',
            Array(
               'articulo'   => $articulo,
               'listado'    => $listado
            )
        );
}