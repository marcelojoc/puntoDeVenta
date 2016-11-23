<?php

require '../main.inc.php';
// require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
// require_once DOL_DOCUMENT_ROOT . '/core/class/html.formorder.class.php';
// require_once DOL_DOCUMENT_ROOT . '/core/class/html.formmargin.class.php';
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
// require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';



$id = (GETPOST('id', 'int') ? GETPOST('id', 'int') : GETPOST('orderid', 'int'));
$ref = GETPOST('ref', 'alpha');
$socid = GETPOST('socid', 'int');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$lineid = GETPOST('lineid', 'int');
$origin = GETPOST('origin', 'alpha');
$originid = (GETPOST('originid', 'int') ? GETPOST('originid', 'int') : GETPOST('origin_id', 'int')); // For backward compatibility

// PDF
$hidedetails = (GETPOST('hidedetails', 'int') ? GETPOST('hidedetails', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DETAILS) ? 1 : 0));
$hidedesc = (GETPOST('hidedesc', 'int') ? GETPOST('hidedesc', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DESC) ? 1 : 0));
$hideref = (GETPOST('hideref', 'int') ? GETPOST('hideref', 'int') : (! empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_REF) ? 1 : 0));






var_dump($_POST);

echo('<br>');

var_dump($_GET);

echo('<br>');

//var_dump($_SESSION);

$object = new Commande($db);
$extrafields = new ExtraFields($db);

	// Add order
if ($action == 'add' && $user->rights->commande->creer)
	{

        echo('entro');
		$datecommande = dol_mktime(12, 0, 0, GETPOST('remonth'), GETPOST('reday'), GETPOST('reyear'));
		$datelivraison = dol_mktime(12, 0, 0, GETPOST('liv_month'), GETPOST('liv_day'), GETPOST('liv_year'));

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
			$object->note_private = GETPOST('note_private'); // nota privada
			$object->note_public = GETPOST('note_public');    // nota publica
			$object->source = GETPOST('source_id');			  
			$object->fk_project = GETPOST('projectid');
			$object->ref_client = GETPOST('ref_client');     // ref cliente
			$object->modelpdf = GETPOST('model');				// modelo de pdf  einstein en este caso
			$object->cond_reglement_id = GETPOST('cond_reglement_id');   // condicion de pago
			$object->mode_reglement_id = GETPOST('mode_reglement_id');   // forma de pago
	        $object->fk_account = GETPOST('fk_account', 'int');          // no se
			$object->availability_id = GETPOST('availability_id');			// tirempo de entrega
			$object->demand_reason_id = GETPOST('demand_reason_id');      // socio,  empleado, socio  etc
			$object->date_livraison = $datelivraison;					// fecha de entrega
	        $object->shipping_method_id = GETPOST('shipping_method_id', 'int');  //  metodo de envio,  seria por el transp   va el num    2
            $object->warehouse_id = GETPOST('warehouse_id', 'int');    // el almacen
			$object->fk_delivery_address = GETPOST('fk_address');      // direccion ...  no interesa en este caso
			$object->contactid = GETPOST('contactid');                // contacto por defecto   va -1  si no tiene ninguno
			$object->fk_incoterms = GETPOST('incoterm_id', 'int');      // no se....
			$object->location_incoterms = GETPOST('location_incoterms', 'alpha');

			// If creation from another object of another module (Example: origin=propal, originid=1)
			if (! empty($origin) && ! empty($originid))
			{
// 				// Parse element/subelement (ex: project_task)
// 				$element = $subelement = $origin;
// 				if (preg_match('/^([^_]+)_([^_]+)/i', $origin, $regs)) {
// 					$element = $regs [1];
// 					$subelement = $regs [2];
// 				}

// 				// For compatibility
// 				if ($element == 'order') {
// 					$element = $subelement = 'commande';
// 				}
// 				if ($element == 'propal') {
// 					$element = 'comm/propal';
// 					$subelement = 'propal';
// 				}
// 				if ($element == 'contract') {
// 					$element = $subelement = 'contrat';
// 				}


// 				$object->origin = $origin;
// 				$object->origin_id = $originid;

// 				// Possibility to add external linked objects with hooks
// 				$object->linked_objects [$object->origin] = $object->origin_id;
// 				$other_linked_objects = GETPOST('other_linked_objects', 'array');
// 				if (! empty($other_linked_objects)) {
// 					$object->linked_objects = array_merge($object->linked_objects, $other_linked_objects);
// 				}

// 				// Fill array 'array_options' with data from add form
// 				$ret = $extrafields->setOptionalsFromPost($extralabels, $object);
// 				if ($ret < 0) $error++;

// 				$object_id = $object->create($user);

// 				if (! $error)
// 				{
// 					//$object_id = $object->create($user);

// 					if ($object_id > 0)
// 					{
// 						dol_include_once('/' . $element . '/class/' . $subelement . '.class.php');

// 						$classname = ucfirst($subelement);
// 						$srcobject = new $classname($db);

// // echo("tu vieja");
// // echo($subelement);
// // echo('<br>');
// // echo($subelement);
// // echo('<br>');
// // echo($subelement);
// // echo('<br>');
// // echo($subelement);
// // echo('<br>');


// 						exit;

// 						dol_syslog("Try to find source object origin=" . $object->origin . " originid=" . $object->origin_id . " to add lines");
// 						$result = $srcobject->fetch($object->origin_id);
// 						if ($result > 0)
// 						{
// 							$lines = $srcobject->lines;
// 							if (empty($lines) && method_exists($srcobject, 'fetch_lines'))
// 							{
// 								$srcobject->fetch_lines();
// 								$lines = $srcobject->lines;
// 							}

// 							$fk_parent_line = 0;
// 							$num = count($lines);

// 							// for($i = 0; $i < $num; $i ++)
// 							// {
// 							// 	$label = (! empty($lines[$i]->label) ? $lines[$i]->label : '');
// 							// 	$desc = (! empty($lines[$i]->desc) ? $lines[$i]->desc : '');
// 							// 	$product_type = (! empty($lines[$i]->product_type) ? $lines[$i]->product_type : 0);

// 							// 	// Dates
// 							// 	// TODO mutualiser
// 							// 	$date_start = $lines[$i]->date_debut_prevue;
// 							// 	if ($lines[$i]->date_debut_reel)
// 							// 		$date_start = $lines[$i]->date_debut_reel;
// 							// 	if ($lines[$i]->date_start)
// 							// 		$date_start = $lines[$i]->date_start;
// 							// 	$date_end = $lines[$i]->date_fin_prevue;
// 							// 	if ($lines[$i]->date_fin_reel)
// 							// 		$date_end = $lines[$i]->date_fin_reel;
// 							// 	if ($lines[$i]->date_end)
// 							// 		$date_end = $lines[$i]->date_end;

// 							// 		// Reset fk_parent_line for no child products and special product
// 							// 	if (($lines[$i]->product_type != 9 && empty($lines[$i]->fk_parent_line)) || $lines[$i]->product_type == 9) {
// 							// 		$fk_parent_line = 0;
// 							// 	}

// 							// 	// Extrafields
// 							// 	if (empty($conf->global->MAIN_EXTRAFIELDS_DISABLED) && method_exists($lines[$i], 'fetch_optionals')) 							// For avoid conflicts if
// 							// 	                                                                                                      // trigger used
// 							// 	{
// 							// 		$lines[$i]->fetch_optionals($lines[$i]->rowid);
// 							// 		$array_options = $lines[$i]->array_options;
// 							// 	}

// 							// 	$result = $object->addline($desc, $lines[$i]->subprice, $lines[$i]->qty, $lines[$i]->tva_tx, $lines[$i]->localtax1_tx, $lines[$i]->localtax2_tx, $lines[$i]->fk_product, $lines[$i]->remise_percent, $lines[$i]->info_bits, $lines[$i]->fk_remise_except, 'HT', 0, $date_start, $date_end, $product_type, $lines[$i]->rang, $lines[$i]->special_code, $fk_parent_line, $lines[$i]->fk_fournprice, $lines[$i]->pa_ht, $label, $array_options, $lines[$i]->fk_unit, $object->origin, $lines[$i]->rowid);

// 							// 	if ($result < 0) {
// 							// 		$error++;
// 							// 		break;
// 							// 	}

// 							// 	// Defined the new fk_parent_line
// 							// 	if ($result > 0 && $lines[$i]->product_type == 9) {
// 							// 		$fk_parent_line = $result;
// 							// 	}
// 							// }

// 							// Hooks
// 							$parameters = array('objFrom' => $srcobject);
// 							$reshook = $hookmanager->executeHooks('createFrom', $parameters, $object, $action); // Note that $action and $object may have been
// 							                                                                               // modified by hook
// 							if ($reshook < 0)
// 								$error++;
// 						} else {
// 							setEventMessages($srcobject->error, $srcobject->errors, 'errors');
// 							$error++;
// 						}
// 					} else {
// 						setEventMessages($object->error, $object->errors, 'errors');
// 						$error++;
// 					}
// 				} else {
// 					// Required extrafield left blank, error message already defined by setOptionalsFromPost()
// 					$action = 'create';
// 				}
			} else {
				// Fill array 'array_options' with data from add form
				$ret = $extrafields->setOptionalsFromPost($extralabels, $object);
				if ($ret < 0) $error++;

				if (! $error)
				{
					$object_id = $object->create($user);

					// If some invoice's lines already known







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
				header('Location: ' . $_SERVER["PHP_SELF"] . '?id=' . $object_id);
				exit();
			} else {
				$db->rollback();
				$action = 'create';
				setEventMessages($object->error, $object->errors, 'errors');
			}
		}
	}




if ($action == 'addline' && $user->rights->commande->creer)
	{
		$langs->load('errors');
		$error = 0;

		// Set if we used free entry or predefined product
		$predef='';
		$product_desc=(GETPOST('dp_desc')?GETPOST('dp_desc'):'');
		$price_ht = GETPOST('price_ht');
		if (GETPOST('prod_entry_mode') == 'free')
		{
			$idprod=0;
			$tva_tx = (GETPOST('tva_tx') ? GETPOST('tva_tx') : 0);
		}
		else
		{
			$idprod=GETPOST('idprod', 'int');
			$tva_tx = '';
		}

		$qty = GETPOST('qty' . $predef);
		$remise_percent = GETPOST('remise_percent' . $predef);

		// Extrafields
		$extrafieldsline = new ExtraFields($db);
		$extralabelsline = $extrafieldsline->fetch_name_optionals_label($object->table_element_line);
		$array_options = $extrafieldsline->getOptionalsFromPost($extralabelsline, $predef);
		// Unset extrafield
		if (is_array($extralabelsline)) {
			// Get extra fields
			foreach ($extralabelsline as $key => $value) {
				unset($_POST["options_" . $key]);
			}
		}



		if (empty($idprod) && ($price_ht < 0) && ($qty < 0)) {
			setEventMessages($langs->trans('ErrorBothFieldCantBeNegative', $langs->transnoentitiesnoconv('UnitPriceHT'), $langs->transnoentitiesnoconv('Qty')), null, 'errors');
			$error++;
		}



		if (GETPOST('prod_entry_mode') == 'free' && empty($idprod) && GETPOST('type') < 0) {
			setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Type')), null, 'errors');
			$error++;
		}
		if (GETPOST('prod_entry_mode') == 'free' && empty($idprod) && (! ($price_ht >= 0) || $price_ht == '')) 	// Unit price can be 0 but not ''
		{
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("UnitPriceHT")), null, 'errors');
			$error++;
		}
		if ($qty == '') {
			setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Qty')), null, 'errors');
			$error++;
		}
		if (GETPOST('prod_entry_mode') == 'free' && empty($idprod) && empty($product_desc)) {
			setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Description')), null, 'errors');
			$error++;
		}

		if (! $error && ($qty >= 0) && (! empty($product_desc) || ! empty($idprod))) {
			// Clean parameters
			$date_start=dol_mktime(GETPOST('date_start'.$predef.'hour'), GETPOST('date_start'.$predef.'min'), GETPOST('date_start'.$predef.'sec'), GETPOST('date_start'.$predef.'month'), GETPOST('date_start'.$predef.'day'), GETPOST('date_start'.$predef.'year'));
			$date_end=dol_mktime(GETPOST('date_end'.$predef.'hour'), GETPOST('date_end'.$predef.'min'), GETPOST('date_end'.$predef.'sec'), GETPOST('date_end'.$predef.'month'), GETPOST('date_end'.$predef.'day'), GETPOST('date_end'.$predef.'year'));
			$price_base_type = (GETPOST('price_base_type', 'alpha')?GETPOST('price_base_type', 'alpha'):'HT');

			// Ecrase $pu par celui du produit
			// Ecrase $desc par celui du produit
			// Ecrase $txtva par celui du produit
			// Ecrase $base_price_type par celui du produit
			if (! empty($idprod)) {
				$prod = new Product($db);
				$prod->fetch($idprod);

				$label = ((GETPOST('product_label') && GETPOST('product_label') != $prod->label) ? GETPOST('product_label') : '');

				// Update if prices fields are defined
					$tva_tx = get_default_tva($mysoc, $object->thirdparty, $prod->id);
					$tva_npr = get_default_npr($mysoc, $object->thirdparty, $prod->id);
					if (empty($tva_tx)) $tva_npr=0;

					$pu_ht = $prod->price;
					$pu_ttc = $prod->price_ttc;
					$price_min = $prod->price_min;
					$price_base_type = $prod->price_base_type;

					// multiprix
					if (! empty($conf->global->PRODUIT_MULTIPRICES) && ! empty($object->thirdparty->price_level))
					{
						$pu_ht = $prod->multiprices[$object->thirdparty->price_level];
						$pu_ttc = $prod->multiprices_ttc[$object->thirdparty->price_level];
						$price_min = $prod->multiprices_min[$object->thirdparty->price_level];
						$price_base_type = $prod->multiprices_base_type[$object->thirdparty->price_level];
						if (! empty($conf->global->PRODUIT_MULTIPRICES_USE_VAT_PER_LEVEL))  // using this option is a bug. kept for backward compatibility
						{
						  if (isset($prod->multiprices_tva_tx[$object->thirdparty->price_level])) $tva_tx=$prod->multiprices_tva_tx[$object->thirdparty->price_level];
						  if (isset($prod->multiprices_recuperableonly[$object->thirdparty->price_level])) $tva_npr=$prod->multiprices_recuperableonly[$object->thirdparty->price_level];
						}
					}
					elseif (! empty($conf->global->PRODUIT_CUSTOMER_PRICES))
					{
						require_once DOL_DOCUMENT_ROOT . '/product/class/productcustomerprice.class.php';

						$prodcustprice = new Productcustomerprice($db);

						$filter = array('t.fk_product' => $prod->id,'t.fk_soc' => $object->thirdparty->id);

						$result = $prodcustprice->fetch_all('', '', 0, 0, $filter);
						if ($result >= 0)
						{
							if (count($prodcustprice->lines) > 0)
							{
								$pu_ht = price($prodcustprice->lines [0]->price);
								$pu_ttc = price($prodcustprice->lines [0]->price_ttc);
								$price_base_type = $prodcustprice->lines [0]->price_base_type;
								$prod->tva_tx = $prodcustprice->lines [0]->tva_tx;
							}
						}
						else
						{
							setEventMessages($prodcustprice->error, $prodcustprice->errors, 'errors');
						}
					}

					// if price ht is forced (ie: calculated by margin rate and cost price)
					if (! empty($price_ht)) {
						$pu_ht = price2num($price_ht, 'MU');
						$pu_ttc = price2num($pu_ht * (1 + ($tva_tx / 100)), 'MU');
					}

					// On reevalue prix selon taux tva car taux tva transaction peut etre different
					// de ceux du produit par defaut (par exemple si pays different entre vendeur et acheteur).
					elseif ($tva_tx != $prod->tva_tx) {
						if ($price_base_type != 'HT') {
							$pu_ht = price2num($pu_ttc / (1 + ($tva_tx / 100)), 'MU');
						} else {
							$pu_ttc = price2num($pu_ht * (1 + ($tva_tx / 100)), 'MU');
						}
					}

					$desc = '';

					// Define output language
					if (! empty($conf->global->MAIN_MULTILANGS) && ! empty($conf->global->PRODUIT_TEXTS_IN_THIRDPARTY_LANGUAGE)) {
						$outputlangs = $langs;
						$newlang = '';
						if (empty($newlang) && GETPOST('lang_id'))
							$newlang = GETPOST('lang_id');
						if (empty($newlang))
							$newlang = $object->thirdparty->default_lang;
						if (! empty($newlang)) {
							$outputlangs = new Translate("", $conf);
							$outputlangs->setDefaultLang($newlang);
						}

						$desc = (! empty($prod->multilangs [$outputlangs->defaultlang] ["description"])) ? $prod->multilangs [$outputlangs->defaultlang] ["description"] : $prod->description;
					} else {
						$desc = $prod->description;
					}

					$desc = dol_concatdesc($desc, $product_desc);

					// Add custom code and origin country into description
					if (empty($conf->global->MAIN_PRODUCT_DISABLE_CUSTOMCOUNTRYCODE) && (! empty($prod->customcode) || ! empty($prod->country_code))) {
						$tmptxt = '(';
						if (! empty($prod->customcode))
							$tmptxt .= $langs->transnoentitiesnoconv("CustomCode") . ': ' . $prod->customcode;
						if (! empty($prod->customcode) && ! empty($prod->country_code))
							$tmptxt .= ' - ';
						if (! empty($prod->country_code))
							$tmptxt .= $langs->transnoentitiesnoconv("CountryOrigin") . ': ' . getCountry($prod->country_code, 0, $db, $langs, 0);
						$tmptxt .= ')';
						$desc = dol_concatdesc($desc, $tmptxt);
					}

				$type = $prod->type;
				$fk_unit = $prod->fk_unit;
			} else {
				$pu_ht = price2num($price_ht, 'MU');
				$pu_ttc = price2num(GETPOST('price_ttc'), 'MU');
				$tva_npr = (preg_match('/\*/', $tva_tx) ? 1 : 0);
				$tva_tx = str_replace('*', '', $tva_tx);
				$label = (GETPOST('product_label') ? GETPOST('product_label') : '');
				$desc = $product_desc;
				$type = GETPOST('type');
				$fk_unit=GETPOST('units', 'alpha');
			}

			// Margin
			$fournprice = price2num(GETPOST('fournprice' . $predef) ? GETPOST('fournprice' . $predef) : '');
			$buyingprice = price2num(GETPOST('buying_price' . $predef) != '' ? GETPOST('buying_price' . $predef) : '');    // If buying_price is '0', we muste keep this value

			// Local Taxes
			$localtax1_tx = get_localtax($tva_tx, 1, $object->thirdparty);
			$localtax2_tx = get_localtax($tva_tx, 2, $object->thirdparty);

			$desc = dol_htmlcleanlastbr($desc);

			$info_bits = 0;
			if ($tva_npr)
				$info_bits |= 0x01;

			if (! empty($price_min) && (price2num($pu_ht) * (1 - price2num($remise_percent) / 100) < price2num($price_min))) {
				$mesg = $langs->trans("CantBeLessThanMinPrice", price(price2num($price_min, 'MU'), 0, $langs, 0, 0, - 1, $conf->currency));
				setEventMessages($mesg, null, 'errors');
			} else {
				// Insert line
				$result = $object->addline($desc, $pu_ht, $qty, $tva_tx, $localtax1_tx, $localtax2_tx, $idprod, $remise_percent, $info_bits, 0, $price_base_type, $pu_ttc, $date_start, $date_end, $type, - 1, 0, GETPOST('fk_parent_line'), $fournprice, $buyingprice, $label, $array_options, $fk_unit);

				if ($result > 0) {
					$ret = $object->fetch($object->id); // Reload to get new records

					if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
						// Define output language
						$outputlangs = $langs;
						$newlang = GETPOST('lang_id', 'alpha');
						if (! empty($conf->global->MAIN_MULTILANGS) && empty($newlang))
							$newlang = $object->thirdparty->default_lang;
						if (! empty($newlang)) {
							$outputlangs = new Translate("", $conf);
							$outputlangs->setDefaultLang($newlang);
						}

						$object->generateDocument($object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref);
					}

					unset($_POST['prod_entry_mode']);

					unset($_POST['qty']);
					unset($_POST['type']);
					unset($_POST['remise_percent']);
					unset($_POST['price_ht']);
					unset($_POST['price_ttc']);
					unset($_POST['tva_tx']);
					unset($_POST['product_ref']);
					unset($_POST['product_label']);
					unset($_POST['product_desc']);
					unset($_POST['fournprice']);
					unset($_POST['buying_price']);
					unset($_POST['np_marginRate']);
					unset($_POST['np_markRate']);
					unset($_POST['dp_desc']);
					unset($_POST['idprod']);
					unset($_POST['units']);

			    	unset($_POST['date_starthour']);
			    	unset($_POST['date_startmin']);
			    	unset($_POST['date_startsec']);
			    	unset($_POST['date_startday']);
			    	unset($_POST['date_startmonth']);
			    	unset($_POST['date_startyear']);
			    	unset($_POST['date_endhour']);
			    	unset($_POST['date_endmin']);
			    	unset($_POST['date_endsec']);
			    	unset($_POST['date_endday']);
			    	unset($_POST['date_endmonth']);
			    	unset($_POST['date_endyear']);
				} else {
					setEventMessages($object->error, $object->errors, 'errors');
				}
			}
		}
	}