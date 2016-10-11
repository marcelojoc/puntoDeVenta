
<?php


require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';


 $obj_facturation= new Facturation();

               // $obj_facturation->set_precio_tabla(500);	// Redefine prix_total_ttc, prix_total_ht et montant_tva from $_SESSION['poscart']
$obj_facturation->id(123);


var_dump($obj_facturation);
$obj_facturation->set_precio_tabla(5555);


var_dump($obj_facturation);


?>