
<?php


require_once '../main.inc.php';
// require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
// require_once DOL_DOCUMENT_ROOT . '/commande/class/commande.class.php';
// include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';  
// require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
// require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
// require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
// require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
// require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
// require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';

// var_dump($_SESSION['poscart']);

//  $obj_pedido= new Commande($db);

//                // $obj_facturation->set_precio_tabla(500);	// Redefine prix_total_ttc, prix_total_ht et montant_tva from $_SESSION['poscart']
// $PRUEBA= $obj_pedido->getNextNumRef(1916);

// $socid=1916;

// 	$soc = new Societe($db);
// 	if ($socid > 0)
// 		$res = $soc->fetch($socid);

// 	$projectid = 0;


// var_dump($res->nom);
// // $obj_pedido->

// $obj_pedido->address= "aaaaaaaaaaaaadddddddddddd";





// $obj_pedido->add_product(6,5, ''  ,''  ,'' );

// $obj_pedido->liste_array();
// id prod,  cantidad, descuento,fecha actual, fecha de entrega

$orig = '24/07/1982';
echo($orig );
$oldDate = '2010-03-20';
$arr = explode('/', $orig);
$newDate = $arr[1].'/'.$arr[0].'/'.$arr[2];

echo('<br>');
echo $newDate;

//$obj_pedido->update();

//var_dump($obj_pedido);


?>