<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2008-2009 Laurent Destailleur <eldy@uers.sourceforge.net>
 * Copyright (C) 2011	   Juanjo Menent	   <jmenent@2byte.es>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/cashdesk/validation_verif.php
 *	\ingroup    cashdesk
 *	\brief      validation_verif.php
 */

require '../main.inc.php';
include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';  
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';


require_once DOL_DOCUMENT_ROOT . '/core/modules/commande/modules_commande.php';
require_once DOL_DOCUMENT_ROOT . '/commande/class/commande.class.php';
require_once DOL_DOCUMENT_ROOT . '/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/order.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
if (! empty($conf->propal->enabled))
	require DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
if (! empty($conf->projet->enabled)) {
	require DOL_DOCUMENT_ROOT . '/projet/class/project.class.php';
	require_once DOL_DOCUMENT_ROOT . '/core/class/html.formprojet.class.php';
}

$obj_facturation = unserialize($_SESSION['serObjFacturation']);
//unset ($_SESSION['serObjFacturation']);

$action =GETPOST('action');
$bankaccountid=$_SESSION['CASHDESK_ID_BANKACCOUNT_CASH'];

switch ($action)
{

	default:

		$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menutpl=validation';

		
		break;


	case 'valide_achat':


		// echo($_POST['txtRecibido'].'<br>');
		// echo($_POST['hiddentxtVuelto'].'<br>');
		// echo($_POST['hiddentxttotal'].'<br>');
		// echo ($_POST['hdnChoix'].'<br>');

		// echo ($_POST['date_now'].'<br>');

		$orig = $_POST['date_now'];
		$arr = explode('/', $orig);
		$newDate = $arr[1].'/'.$arr[0].'/'.$arr[2];
		// echo ($newDate);
		// echo($_POST['txtRecibido'].'<br>');



		$obj_facturation->prixTotalTtc($_POST['hiddentxttotal']);
	


		$company=new Societe($db);
		$company->fetch($conf->global->CASHDESK_ID_THIRDPARTY);

		$invoice=new Facture($db);
		$invoice->date=dol_now();   // fecha de la factura
		$invoice->type= Facture::TYPE_STANDARD;  //tipo de factura
		
		
		$num=$invoice->getNextNumRef($company);  // dame el proximo numero de factura para el cliente ....

		$obj_facturation->numInvoice($num);       // asigna el numero nuevo de factura

		$obj_facturation->getSetPaymentMode($_POST['hdnChoix']);    // seleccion del medio de pago




		// Si paiement autre qu'en especes, montant encaisse = prix total
		$mode_reglement = $obj_facturation->getSetPaymentMode();



		// ESP es de contado     DIF es el otro...cuenta corriente seria
		if ( $mode_reglement != 'ESP' ) {
			$montant = $obj_facturation->prixTotalTtc();  // cantidad
		} else {
			$montant = $_POST['txtRecibido'];            // cantidad 
		}
		

		if ( $mode_reglement != 'DIF') {
			$obj_facturation->montantEncaisse($montant); // importe en efectivo
			$obj_facturation->paiementLe('RESET');
			//Determination de la somme rendue
			$total = $obj_facturation->prixTotalTtc();
			$encaisse = $obj_facturation->montantEncaisse(); // saldo en efectivo

			$obj_facturation->montantRendu($encaisse - $total);
			

		}
		else
		{

			$obj_facturation->montantEncaisse('RESET');    // borro  los datos de recibido  y cambio
			$obj_facturation->montantRendu('RESET');
		    //$txtDatePaiement=$_POST['txtDatePaiement'];
		    // $datePaiement=dol_mktime(0,0,0,$_POST['txtDatePaiementmonth'],$_POST['txtDatePaiementday'],$_POST['txtDatePaiementyear']);
		    // $txtDatePaiement=dol_print_date($datePaiement,'dayrfc');
			$obj_facturation->paiementLe($newDate);
		}

		

          $redirection = 'validation.php';
		//$redirection = 'affIndex_bkp.php?menutpl=validation';
		
		break;





	case 'crear_remito':



		//var_dump($_SESSION['poscart']);

		$id = (GETPOST('id', 'int') ? GETPOST('id', 'int') : GETPOST('orderid', 'int'));
		$ref = GETPOST('ref', 'alpha');
		$socid = $_SESSION['CASHDESK_ID_THIRDPARTY'];  // el id del Cliente al que le estas vendiendo
		//$action = GETPOST('action', 'alpha');
		$confirm = GETPOST('confirm', 'alpha');
		$lineid = GETPOST('lineid', 'int');
		$origin = GETPOST('origin', 'alpha');
		$originid = (GETPOST('originid', 'int') ? GETPOST('originid', 'int') : GETPOST('origin_id', 'int')); // For backward compatibility
		$totalPrice = GETPOST('hidenTotal', 'alpha');
		// PDF
		$hidedetails = (GETPOST('hidedetails', 'int') ? GETPOST('hidedetails', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DETAILS) ? 1 : 0));
		$hidedesc = (GETPOST('hidedesc', 'int') ? GETPOST('hidedesc', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DESC) ? 1 : 0));
		$hideref = (GETPOST('hideref', 'int') ? GETPOST('hideref', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_REF) ? 1 : 0));

		if (! empty($user->societe_id))
			$socid = $user->societe_id;
		$result = restrictedArea($user, 'commande', $id);

		$object = new Commande($db);
		$extrafields = new ExtraFields($db);

//var_dump($object);

// $numer= $object->getNextNumRef(124);

// echo($numer);


// var_dump($object->getLinesArray());


		

if ($action == 'crear_remito' && $user->rights->commande->creer)
	{

        
		$datecommande =  date("Y-m-d");

		$datelivraison = date("Y-m-d");


		if ($datecommande == '') {
			setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentities('Date')), null, 'errors');
			$action = 'create';
			$error++;
		}

		if ($socid < 1) {
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Customer")), null, 'errors');
			$action = 'create';
			$error++;
		}

		if (! $error) {
			$object->socid = $socid;
			$object->fetch_thirdparty();

			$db->begin();

			$object->date_commande = $datecommande;
			$object->note_private = 'Generada por '.$_SESSION['firstname'].' '.$_SESSION['lastname'];
			$object->note_public = GETPOST('txtaNotes');
			$object->source = GETPOST('source_id');
			$object->fk_project = GETPOST('projectid');
			$object->ref_client = " ref-".$_SESSION['firstname'];  
			$object->modelpdf = 'einstein';
			$object->cond_reglement_id = '1';
			$object->mode_reglement_id = '4';
	        $object->fk_account = GETPOST('fk_account', 'int');
			$object->availability_id = '2';
			$object->demand_reason_id = '6';
			$object->date_livraison = $datelivraison;
	        $object->shipping_method_id = '2';
            $object->warehouse_id = $_SESSION['CASHDESK_ID_WAREHOUSE'];
			$object->fk_delivery_address = GETPOST('fk_address');
			$object->contactid = GETPOST('contactid');
			$object->fk_incoterms = GETPOST('incoterm_id', 'int');
			$object->location_incoterms = GETPOST('location_incoterms', 'alpha');



			// If creation from another object of another module (Example: origin=propal, originid=1)
			if (! empty($origin) && ! empty($originid))
			{



			} 
			else {
				//Fill array 'array_options' with data from add form
				$ret = $extrafields->setOptionalsFromPost($extralabels, $object);
				if ($ret < 0) $error++;

				if (! $error)
				{

					
					$object_id = $object->create($user);

					// Aqui va el loop para guardar en la bd los productos con los precios nuevos



					}

				}
			}

			// Insert default contacts if defined
			if ($object_id > 0)
			{
				if (GETPOST('contactid'))
				{
					$result = $object->add_contact(GETPOST('contactid'), 'CUSTOMER', 'external');
					if ($result < 0) {
						setEventMessages($langs->trans("ErrorFailedToAddContact"), null, 'errors');
						$error++;
					}
				}

				$id = $object_id;
				$action = '';
			}

			// End of object creation, we show it
			if ($object_id > 0 && ! $error)
			{
				$db->commit();
				//header('Location: ' . $_SERVER["PHP_SELF"] . '?id=' . $object_id);

					// Get content of cart
					$tab_liste = $_SESSION['poscart'];


					$sql=  "INSERT INTO `dolibar`.`llx_commandedet`(`rowid`,`fk_commande`,`fk_parent_line`,
					`fk_product`,`label`,`description`,`tva_tx`,`localtax1_tx`,`localtax1_type`,`localtax2_tx`,
					`localtax2_type`,`qty`,`remise_percent`,`remise`,`fk_remise_except`,`price`,`subprice`,
					`total_ht`,`total_tva`,`total_localtax1`,`total_localtax2`,`total_ttc`,`product_type`,
					`date_start`,`date_end`,`info_bits`,`buy_price_ht`,`fk_product_fournisseur_price`,
					`special_code`,`rang`,`fk_unit`,`import_key`)";



					// Loop on each line into cart
					$tab_liste_size=count($tab_liste);
					$db->begin(); // Debut transaction
					for ($i=0; $i < $tab_liste_size; $i++)
					{

						$postcart = "VALUES ( NULL,'$object_id',
						NULL,'".$tab_liste[$i]['fk_article']."',
						'".$tab_liste[$i]['label']."',
						'".$tab_liste[$i]['label']."',
						NULL,
						'".$tab_liste[$i]['total_localtax1']."',
						NULL,'".$tab_liste[$i]['total_localtax2']."',
						'0',
						'".$tab_liste[$i]['qte']."',
						'".$tab_liste[$i]['remise_percent']."',
						'".$tab_liste[$i]['remise']."',
						'0',
						'".$tab_liste[$i]['price']."',
						'".$tab_liste[$i]['price_ttc']."',
						'".$tab_liste[$i]['total_ht']."',
						'".$tab_liste[$i]['total_vat']."',
						'0.00000000',
						'0.00000000',
						'".$tab_liste[$i]['total_ttc']."',
						'0',NULL,NULL,
						'0','0.00000000',
						NULL,'0','1',NULL,NULL)";

						

// echo($sql.$postcart);

// echo('<br>');

// echo($tab_liste[$i]['label']);

// 						exit;
						$db->query($sql.$postcart);

						$db->commit(); // Valide




						//========================================================


						$updatePrice = "UPDATE `dolibar`.`llx_commande` SET `total_ht`='$totalPrice',`total_ttc`='$totalPrice' WHERE `rowid`='$object_id'"; 
					
						$db->begin();

						$db->query($updatePrice);

						$db->commit(); // Valide

//==================================================================================0

								$idwarehouse = $_SESSION['CASHDESK_ID_WAREHOUSE'];

										$qualified_for_stock_change=0;
										if (empty($conf->global->STOCK_SUPPORTS_SERVICES))
										{
											$qualified_for_stock_change=$object->hasProductsOrServices(2);
										}
										else
										{
											$qualified_for_stock_change=$object->hasProductsOrServices(1);
										}

										// Check parameters
										if (! empty($conf->stock->enabled) && ! empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $qualified_for_stock_change)
										{
											if (! $idwarehouse || $idwarehouse == -1)
											{
												$error++;
												setEventMessages($langs->trans('ErrorFieldRequired',$langs->transnoentitiesnoconv("Warehouse")), null, 'errors');
												$action='';
											}
										}

										if (! $error) {
											$result = $object->valid($user, $idwarehouse);
											if ($result >= 0)
											{
												// Define output language
												if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE))
												{
													$outputlangs = $langs;
													$newlang = '';
													if ($conf->global->MAIN_MULTILANGS && empty($newlang) && GETPOST('lang_id')) $newlang = GETPOST('lang_id','alpha');
													if ($conf->global->MAIN_MULTILANGS && empty($newlang))	$newlang = $object->thirdparty->default_lang;
													if (! empty($newlang)) {
														$outputlangs = new Translate("", $conf);
														$outputlangs->setDefaultLang($newlang);
													}
													$model=$object->modelpdf;
													$ret = $object->fetch($id); // Reload to get new records

													$object->generateDocument($model, $outputlangs, $hidedetails, $hidedesc, $hideref);
												}
											}
											else
											{
												setEventMessages($object->error, $object->errors, 'errors');
											}
										}










						//===================================================Genera el comprobante de venta

										


					}

				$redirection = 'validation_verif.php?action=valide_facture';
				//$redirection = 'validacion_ok.php?menutpl=validation_ok&facid='.$id;
				
			} else {
				$db->rollback();
				$action = 'create';
				setEventMessages($object->error, $object->errors, 'errors');
			}
		}

		break;



	case 'retour':

		$redirection = 'affIndex.php?menutpl=facturation';
		break;


	case 'valide_facture':

		$now=dol_now();

		// Recuperation de la date et de l'heure
		$date = dol_print_date($now,'day');
		$heure = dol_print_date($now,'hour');

		$note = '';
		if (! is_object($obj_facturation))
		{
			dol_print_error('','Empty context');
			exit;
		}

		switch ( $obj_facturation->getSetPaymentMode() )
		{
			case 'DIF':
				$mode_reglement_id = 0;
				//$cond_reglement_id = dol_getIdFromCode($db,'RECEP','cond_reglement','code','rowid')
				$cond_reglement_id = 0;
				break;
			case 'ESP':
				$mode_reglement_id = dol_getIdFromCode($db,'LIQ','c_paiement');
				$cond_reglement_id = 0;
				$note .= $langs->trans("Cash")."\n";
				$note .= $langs->trans("Received").' : '.$obj_facturation->montantEncaisse()." ".$conf->currency."\n";
				$note .= $langs->trans("Rendu").' : '.$obj_facturation->montantRendu()." ".$conf->currency."\n";
				$note .= "\n";
				$note .= '--------------------------------------'."\n\n";
				break;
			case 'CB':
				$mode_reglement_id = dol_getIdFromCode($db,'CB','c_paiement');
				$cond_reglement_id = 0;
				break;
			case 'CHQ':
				$mode_reglement_id = dol_getIdFromCode($db,'CHQ','c_paiement');
				$cond_reglement_id = 0;
				break;
		}
		if (empty($mode_reglement_id)) $mode_reglement_id=0;	// If mode_reglement_id not found
		if (empty($cond_reglement_id)) $cond_reglement_id=0;	// If cond_reglement_id not found
		$note .= $_POST['txtaNotes'];
		dol_syslog("obj_facturation->getSetPaymentMode()=".$obj_facturation->getSetPaymentMode()." mode_reglement_id=".$mode_reglement_id." cond_reglement_id=".$cond_reglement_id);


		$error=0;


		$db->begin();

		$user->fetch($_SESSION['uid']);
		$user->getrights();

		$thirdpartyid = $_SESSION['CASHDESK_ID_THIRDPARTY'];
		$societe = new Societe($db);
		$societe->fetch($thirdpartyid);

		$invoice=new Facture($db);

		// Get content of cart
		$tab_liste = $_SESSION['poscart'];

		// Loop on each line into cart
		$tab_liste_size=count($tab_liste);
		for ($i=0; $i < $tab_liste_size; $i++)
		{
			$tmp = getTaxesFromId($tab_liste[$i]['fk_tva']);
			$vat_rate = $tmp['rate'];
			$vat_npr = $tmp['npr'];

			$invoiceline=new FactureLigne($db);
			$invoiceline->fk_product=$tab_liste[$i]['fk_article'];
			$invoiceline->desc=$tab_liste[$i]['label'];
			$invoiceline->qty=$tab_liste[$i]['qte'];
			$invoiceline->remise_percent=$tab_liste[$i]['remise_percent'];
			$invoiceline->price=$tab_liste[$i]['price'];
			$invoiceline->subprice=$tab_liste[$i]['price'];
			
			$invoiceline->tva_tx=empty($vat_rate)?0:$vat_rate;	// works even if vat_rate is ''
			$invoiceline->info_bits=empty($vat_npr)?0:$vat_npr;
				
			$invoiceline->total_ht=$tab_liste[$i]['total_ht'];
			$invoiceline->total_ttc=$tab_liste[$i]['total_ttc'];
			$invoiceline->total_tva=$tab_liste[$i]['total_vat'];
			$invoiceline->total_localtax1=$tab_liste[$i]['total_localtax1'];
			$invoiceline->total_localtax2=$tab_liste[$i]['total_localtax2'];
			$invoice->lines[]=$invoiceline;
		}

		$invoice->socid=$conf_fksoc;
		$invoice->date_creation=$now;
		$invoice->date=$now;
		$invoice->date_lim_reglement=0;
		$invoice->total_ht=$obj_facturation->prixTotalHt();
		$invoice->total_tva=$obj_facturation->montantTva();
		$invoice->total_ttc=$obj_facturation->prixTotalTtc();
		$invoice->note_private=$note;
		$invoice->cond_reglement_id=$cond_reglement_id;
		$invoice->mode_reglement_id=$mode_reglement_id;


		//var_dump($invoice);
		//print "c=".$invoice->cond_reglement_id." m=".$invoice->mode_reglement_id; exit;

		// Si paiement differe ...
		if ( $obj_facturation->getSetPaymentMode() == 'DIF' )
		{
			$resultcreate=$invoice->create($user,0,dol_stringtotime($obj_facturation->paiementLe()));
			if ($resultcreate > 0)
			{
				$warehouseidtodecrease=(isset($_SESSION["CASHDESK_ID_WAREHOUSE"])?$_SESSION["CASHDESK_ID_WAREHOUSE"]:0);
				if (! empty($conf->global->CASHDESK_NO_DECREASE_STOCK)) $warehouseidtodecrease=0;	// If a particular stock is defined, we disable choice
				
				$resultvalid=$invoice->validate($user, $obj_facturation->numInvoice(), 0);

				if ($warehouseidtodecrease > 0)
				{
					// Decrease
					require_once DOL_DOCUMENT_ROOT.'/product/stock/class/mouvementstock.class.php';
					$langs->load("agenda");
					// Loop on each line
					$cpt=count($invoice->lines);
					for ($i = 0; $i < $cpt; $i++)
					{
						if ($invoice->lines[$i]->fk_product > 0)
						{
							$mouvP = new MouvementStock($db);
							$mouvP->origin = &$invoice;
							// We decrease stock for product
							if ($invoice->type == $invoice::TYPE_CREDIT_NOTE) $result=$mouvP->reception($user, $invoice->lines[$i]->fk_product, $warehouseidtodecrease, $invoice->lines[$i]->qty, $invoice->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarrFromPos",$invoice->newref));
							else $result=$mouvP->livraison($user, $invoice->lines[$i]->fk_product, $warehouseidtodecrease, $invoice->lines[$i]->qty, $invoice->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarrFromPos",$invoice->newref));
							if ($result < 0) {
								$error++;
							}
						}
					}
				}
			}
			else
			{
				$error++;
			}

			$id = $invoice->id;
		}
		else
		{
			$resultcreate=$invoice->create($user,0,0);
			if ($resultcreate > 0)
			{
				$warehouseidtodecrease=(isset($_SESSION["CASHDESK_ID_WAREHOUSE"])?$_SESSION["CASHDESK_ID_WAREHOUSE"]:0);
				if (! empty($conf->global->CASHDESK_NO_DECREASE_STOCK)) $warehouseidtodecrease=0;	// If a particular stock is defined, we disable choice
				
				$resultvalid=$invoice->validate($user, $obj_facturation->numInvoice(), 0);

				if ($warehouseidtodecrease > 0)
				{
					// Decrease
					require_once DOL_DOCUMENT_ROOT.'/product/stock/class/mouvementstock.class.php';
					$langs->load("agenda");
					// Loop on each line
					$cpt=count($invoice->lines);
					for ($i = 0; $i < $cpt; $i++)
					{
						if ($invoice->lines[$i]->fk_product > 0)
						{
							$mouvP = new MouvementStock($db);
							$mouvP->origin = &$invoice;
							// We decrease stock for product
							if ($invoice->type == $invoice::TYPE_CREDIT_NOTE) $result=$mouvP->reception($user, $invoice->lines[$i]->fk_product, $warehouseidtodecrease, $invoice->lines[$i]->qty, $invoice->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarrFromPos",$invoice->newref));
							else $result=$mouvP->livraison($user, $invoice->lines[$i]->fk_product, $warehouseidtodecrease, $invoice->lines[$i]->qty, $invoice->lines[$i]->subprice, $langs->trans("InvoiceValidatedInDolibarrFromPos",$invoice->newref));
							if ($result < 0) {
								$error++;
							}
						}
					}
				}

				$id = $invoice->id;

				// Add the payment
				$payment=new Paiement($db);
				$payment->datepaye=$now;
				$payment->bank_account=$conf_fkaccount;
				$payment->amounts[$invoice->id]=$obj_facturation->prixTotalTtc();
				$payment->note=$langs->trans("Payment").' '.$langs->trans("Invoice").' '.$obj_facturation->numInvoice();
				$payment->paiementid=$invoice->mode_reglement_id;
				$payment->num_paiement='';

				$paiement_id = $payment->create($user);
				if ($paiement_id > 0)
				{
                    if (! $error)
                    {
                        $result=$payment->addPaymentToBank($user, 'payment', '(CustomerInvoicePayment)', $bankaccountid, '', '');
                        if (! $result > 0)
                        {
                            $errmsg=$paiement->error;
                            $error++;
                        }
                    }

                    if (! $error)
                    {
                    	if ($invoice->total_ttc == $obj_facturation->prixTotalTtc()
                    		&& $obj_facturation->getSetPaymentMode() != 'DIFF')
                    	{
                    		// We set status to payed
                    		$result=$invoice->set_paid($user);
                  			//print 'eeeee';exit;
                    	}

                    }
				}
				else
				{
					$error++;
				}
			}
			else
			{
				$error++;
			}
		}

		if (! $error)
		{
			$db->commit();
			//$redirection = 'affIndex.php?menutpl=validation_ok&facid='.$id;	// Ajout de l'id de la facture, pour l'inclure dans un lien pointant directement vers celle-ci dans Dolibarr
			$redirection = 'validacion_ok.php?menutpl=validation_ok&facid='.$id;

		}
		else
		{
			$db->rollback();
			$redirection = 'validacion_ok.php?facid='.$id.'&mesg=error';	// Ajout de l'id de la facture, pour l'inclure dans un lien pointant directement vers celle-ci dans Dolibarr
		}

		
		break;

		// End of case: valide_facture
}






$_SESSION['serObjFacturation'] = serialize($obj_facturation);

header('Location: '.$redirection);