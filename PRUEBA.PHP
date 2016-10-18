
<?php


require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
require_once DOL_DOCUMENT_ROOT . '/commande/class/commande.class.php';


 $obj_pedido= new Commande($db);

               // $obj_facturation->set_precio_tabla(500);	// Redefine prix_total_ttc, prix_total_ht et montant_tva from $_SESSION['poscart']
$PRUEBA= $obj_pedido->getNextNumRef(1916);


var_dump($PRUEBA);
// $obj_pedido->

$obj_pedido->address= "aaaaaaaaaaaaadddddddddddd";

$obj_pedido->add_product(6,5, ''  ,''  ,'' );

// id prod,  cantidad, descuento,fecha actual, fecha de entrega

$obj_pedido->update();

 var_dump($obj_pedido);


?>