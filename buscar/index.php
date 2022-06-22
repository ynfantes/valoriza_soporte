<?php
require_once '../includes/constants.php';
$accion = isset($_GET['action'])? $_GET['action']:'';
$lista = array();
$articulos = new articulos();
switch ($accion) {
    case 'related_get':
        $r = $articulos->listarPopulares(10);
        if ($r['suceed'] && count($r['data'])>0) {
            foreach ($r['data'] as $item) {
                $lista[] = Array('label'=>$item['titulo'],'url'=>ROOT.'?item='.$item['id']);
            }
        }
        echo json_encode($lista);
        break;
    
    case 'suggest_get':
        $r = $articulos->listarArticulos();
        if ($r['suceed'] && count($r['data'])>0) {
            foreach ($r['data'] as $item) {
                $lista[] = Array('label'=>$item['titulo'],'url'=>ROOT.'?item='.$item['id']);
            }
        }
        echo json_encode($lista);
        break;
    
    // <editor-fold defaultstate="collapsed" desc="buscar, default">
    default:
        $listado = array();
        if (isset($_GET['s'])) {
            $s = $_GET['s'];
            $s = str_replace(' ', '%', $s);
            $r = $articulos->buscarArticulosPorContenido($s);
            if ($r['suceed']) {
                $listado = $r['data'];
                for ($i=0;$i <count($listado); $i++) {
                    $t = $articulos->obtenerTemasArticulo($listado[$i]['id']);
                    if (count($t)>0) {
                        $listado[$i]['topic'] = $t[0]['id_tema'];
                    }
                }
            }
            echo $twig->render('resultado.html.twig', Array(
                'listado' => $listado,
                's'       => $_GET['s']));
            break;
        }
        
    // </editor-fold>

}
