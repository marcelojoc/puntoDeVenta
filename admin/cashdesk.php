<?php
/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Juanjo Menent		<jmenent@2byte.es>
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
 *	\file       htdocs/cashdesk/admin/cashdesk.php
 *	\ingroup    cashdesk
 *	\brief      Setup page for cashdesk module
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';

// If socid provided by ajax company selector
if (! empty($_REQUEST['CASHDESK_ID_THIRDPARTY_id']))
{
	$_GET['CASHDESK_ID_THIRDPARTY'] = GETPOST('CASHDESK_ID_THIRDPARTY_id','alpha');
	$_POST['CASHDESK_ID_THIRDPARTY'] = GETPOST('CASHDESK_ID_THIRDPARTY_id','alpha');
	$_REQUEST['CASHDESK_ID_THIRDPARTY'] = GETPOST('CASHDESK_ID_THIRDPARTY_id','alpha');
}



// Security check
if (!$user->admin)
accessforbidden();

$langs->load("admin");
$langs->load("cashdesk");

/*
 * Actions
 */
if (GETPOST('action','alpha') == 'set')
{
	$db->begin();

	if (GETPOST('socid','int') < 0) $_POST["socid"]='';

	$res = dolibarr_set_const($db,"CASHDESK_ID_THIRDPARTY",(GETPOST('socid','int') > 0 ? GETPOST('socid','int') : ''),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_ID_BANKACCOUNT_CASH",(GETPOST('CASHDESK_ID_BANKACCOUNT_CASH','alpha') > 0 ? GETPOST('CASHDESK_ID_BANKACCOUNT_CASH','alpha') : ''),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_ID_BANKACCOUNT_CHEQUE",(GETPOST('CASHDESK_ID_BANKACCOUNT_CHEQUE','alpha') > 0 ? GETPOST('CASHDESK_ID_BANKACCOUNT_CHEQUE','alpha') : ''),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_ID_BANKACCOUNT_CB",(GETPOST('CASHDESK_ID_BANKACCOUNT_CB','alpha') > 0 ? GETPOST('CASHDESK_ID_BANKACCOUNT_CB','alpha') : ''),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_ID_WAREHOUSE",(GETPOST('CASHDESK_ID_WAREHOUSE','alpha') > 0 ? GETPOST('CASHDESK_ID_WAREHOUSE','alpha') : ''),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_NO_DECREASE_STOCK",GETPOST('CASHDESK_NO_DECREASE_STOCK','alpha'),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_SERVICES", GETPOST('CASHDESK_SERVICES','alpha'),'chaine',0,'',$conf->entity);
	$res = dolibarr_set_const($db,"CASHDESK_DOLIBAR_RECEIPT_PRINTER", GETPOST('CASHDESK_DOLIBAR_RECEIPT_PRINTER','alpha'),'chaine',0,'',$conf->entity);

// conf de categorias a las cuales aplicar
	//$res = dolibarr_set_const($db,"TPV_DESCUENTO_ESCALONADO", GETPOST('TPV_DESCUENTO_ESCALONADO','int') ,'chaine',0,'',$conf->entity);



	$area = $_POST['TPV_DESCUENTO_ESCALONADO'];

	($area =="") ? $area = -1 : $area = $area;  // si esta vacia la variable asigno inmediatamente -1 para afectar a todos


	$db->begin(); // Debut transaction

		if(is_null($conf->global->TPV_DESCUENTO_ESCALONADO))  // si no existe este parametro  lo debo crear
		{

			$resp = $db->query("INSERT INTO llx_const(rowid,`name`,`entity`,`value`,`type`,`visible`,`note`,`tms`) 
						VALUES ( NULL,'TPV_DESCUENTO_ESCALONADO','1','".$area."','chaine','0',NULL,CURRENT_TIMESTAMP)");

		}
		else     // si existe esta constante la modifico

		{
			$resp = $db->query("UPDATE llx_const SET value= '".$area."' WHERE name='TPV_DESCUENTO_ESCALONADO'");
			
		}


		// verifico si la consulta esta bien  para hacerla
		if ($resp)
		{
			$db->commit();  // Valide
			
		}
		else
		{
			$db->rollback();  // Annule
			print dol_print_error($db);
			
		}



	dol_syslog("admin/cashdesk: level ".GETPOST('level','alpha'));

	if (! $res > 0) $error++;

 	if (! $error)
    {
        $db->commit();
	    setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
    }
    else
    {
        $db->rollback();
	    setEventMessages($langs->trans("Error"), null, 'errors');


    }


}

/*
 * View
 */

$form=new Form($db);
$formproduct=new FormProduct($db);

llxHeader('',$langs->trans("CashDeskSetup"));
	// var_dump($conf->global->GET_LOCATION_CUSTOMERS);

	// if(is_null($conf->global->GET_LOCATION_CUSTOMERS)){

	// print'variable no seteada';
	// }

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print load_fiche_titre($langs->trans("CashDeskSetup"),$linkback,'title_setup');
print '<br>';


// Mode
$var=true;
print '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="set">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameters").'</td><td>'.$langs->trans("Value").'</td>';
print "</tr>\n";
$var=!$var;
print '<tr '.$bc[$var].'><td width=\"50%\">'.$langs->trans("CashDeskThirdPartyForSell").'</td>';
print '<td colspan="2">';
print $form->select_company($conf->global->CASHDESK_ID_THIRDPARTY,'socid','s.client in (1,3)',1,0,1,array(),0);
print '</td></tr>';


if (! empty($conf->banque->enabled))
{
	$var=!$var;
	print '<tr '.$bc[$var].'><td>'.$langs->trans("CashDeskBankAccountForSell").'</td>';
	print '<td colspan="2">';
	$form->select_comptes($conf->global->CASHDESK_ID_BANKACCOUNT_CASH,'CASHDESK_ID_BANKACCOUNT_CASH',0,"courant=2",1);
	print '</td></tr>';

	$var=!$var;
	print '<tr '.$bc[$var].'><td>'.$langs->trans("CashDeskBankAccountForCheque").'</td>';
	print '<td colspan="2">';
	$form->select_comptes($conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE,'CASHDESK_ID_BANKACCOUNT_CHEQUE',0,"courant=1",1);
	print '</td></tr>';

	$var=!$var;
	print '<tr '.$bc[$var].'><td>'.$langs->trans("CashDeskBankAccountForCB").'</td>';
	print '<td colspan="2">';
	$form->select_comptes($conf->global->CASHDESK_ID_BANKACCOUNT_CB,'CASHDESK_ID_BANKACCOUNT_CB',0,"courant=1",1);
	print '</td></tr>';
}

if (! empty($conf->stock->enabled))
{
	$var=!$var;
	print '<tr '.$bc[$var].'><td>'.$langs->trans("CashDeskDoNotDecreaseStock").'</td>';	// Force warehouse (this is not a default value)
	print '<td colspan="2">';
	if (empty($conf->productbatch->enabled)) {
	   print $form->selectyesno('CASHDESK_NO_DECREASE_STOCK',$conf->global->CASHDESK_NO_DECREASE_STOCK,1);
	}
	else
	{
	    if (!$conf->global->CASHDESK_NO_DECREASE_STOCK) {
	       $res = dolibarr_set_const($db,"CASHDESK_NO_DECREASE_STOCK",1,'chaine',0,'',$conf->entity);
	    }
	    print $langs->trans('StockDecreaseForPointOfSaleDisabledbyBatch');
	}
	print '</td></tr>';

	$disabled=$conf->global->CASHDESK_NO_DECREASE_STOCK;

	$var=!$var;
	print '<tr '.$bc[$var].'><td>'.$langs->trans("CashDeskIdWareHouse").'</td>';	// Force warehouse (this is not a default value)
	print '<td colspan="2">';
	if (! $disabled)
	{
		print $formproduct->selectWarehouses($conf->global->CASHDESK_ID_WAREHOUSE,'CASHDESK_ID_WAREHOUSE','',1,$disabled);
		print ' <a href="'.DOL_URL_ROOT.'/product/stock/card.php?action=create&backtopage='.urlencode($_SERVER["PHP_SELF"]).'">('.$langs->trans("Create").')</a>';
	}
	else
	{
		print $langs->trans("StockDecreaseForPointOfSaleDisabled");
	}


// configuracion descuento escalonado
	$disabled=$conf->global->TPV_DESCUENTO_ESCALONADO;
	$var=!$var;
	print '<tr '.$bc[$var].'><td> Aplicar descuento escalonado al Area : </td>';
	print '<td colspan="2">';

		// print $formproduct->selectWarehouses($conf->global->CASHDESK_ID_WAREHOUSE,'CASHDESK_ID_WAREHOUSE','',1,$disabled);
		// print ' <a href="'.DOL_URL_ROOT.'/product/stock/card.php?action=create&backtopage='.urlencode($_SERVER["PHP_SELF"]).'">('.$langs->trans("Create").')</a>';


// $disabled  es el valor que tiene la constante para configurarla

	$paramentro= 'Area';

	$sql_param= "SELECT param FROM llx_extrafields WHERE elementtype = 'societe' AND label = '" .$paramentro."'";

        
        $loadOption = $db->query($sql_param);
        $num = $db->num_rows($loadOption);
        // si devuelve producto  entro al proceso
		
        if ($num)
        {

            $obj = $db->fetch_object($loadOption);
            if ($obj)

            {


				$area_conf     = $conf->global->TPV_DESCUENTO_ESCALONADO;

                print'<select id="TPV_DESCUENTO_ESCALONADO" class="flat minwidth100" name="TPV_DESCUENTO_ESCALONADO">'; // inicio del select
				$areas = unserialize($obj->param);

				// esta es una opcion que no puede estar en la base de datos  
				if ( $area_conf == -1)
				{

					print'<option value="-1" selected="" > Afectar a todos los Clientes</option>';
				}
				else{

					print'<option value="-1"> Afectar a todos los Clientes</option>';
				}


					// recorro  el arreglo y dejo seleccionada la opcion que esta seteada	
					foreach($areas['options'] as $id => $area)
					{

						// seleccionar e
						if( $id == $area_conf) // estamos en presencia de la variable configurada que hay que seleccionar
						{

							print'<option value="'.$id.'" selected="" > '.$area.' </option>';
						}
						else
						{

							print'<option value="'.$id.'"> '.$area.' </option>';
						}
						

					}

            }
        }
		else
		{

			print'<option value="0 "> Campos extra No asignados </option>';

		}

        print '</select>';    // fin del select



	print '</td></tr>';
}

// todo =================== opcion configurable de activar geolocalizacion de clientes
print '<tr '.$bc[$var].'><td>Activar geolocalizacion por cliente con venta </td>';
	print '<td colspan="2">';
	print'<select class="flat" id="GET_LOCATION_CUSTOMERS" name="GET_LOCATION_CUSTOMERS">
<option value="1">Sí</option>
<option value="0" selected="">No</option>
</select>';
	print '</td></tr>';


//=====================================================


if (! empty($conf->service->enabled))
{
    $var=! $var;
    print '<tr '.$bc[$var].'><td>';
    print $langs->trans("CashdeskShowServices");
    print '<td colspan="2">';
    print $form->selectyesno("CASHDESK_SERVICES",$conf->global->CASHDESK_SERVICES,1);
    print "</td></tr>\n";
}

// Use Dolibarr Receipt Printer
if (! empty($conf->receiptprinter->enabled))
{
    $var=! $var;
    print '<tr '.$bc[$var].'><td>';
    print $langs->trans("DolibarrReceiptPrinter").' ('.$langs->trans("FeatureNotYetAvailable").')';
    print '<td colspan="2">';
    print $form->selectyesno("CASHDESK_DOLIBAR_RECEIPT_PRINTER",$conf->global->CASHDESK_DOLIBAR_RECEIPT_PRINTER,1);
    print "</td></tr>\n";
}

print '</table>';
print '<br>';

print '<div class="center"><input type="submit" class="button" value="'.$langs->trans("Save").'"></div>';

print "</form>\n";

llxFooter();
$db->close();
