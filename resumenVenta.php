<?php
include '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';

$langs->load("admin");
$langs->load("cashdesk");

// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}

var_dump($_SESSION);

?>




<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Punto de venta</title>
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">
  

        <body>


                <div class="container">    



                    <div class="container"><h3> Vendedor <?php echo($_SESSION['lastname']) ?></h3></div>





                        <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Resumen</a></li>
                        <li><a data-toggle="tab" href="#menu1">Comprobantes</a></li>

                        </ul>

                        <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                
                                <!--este es el bloque de tab1-->





<?php



$form=new Form($db);
$productstatic=new Product($db);

    $id=$_SESSION['CASHDESK_ID_WAREHOUSE'];
	if ($id)
	{
		$object = new Entrepot($db);
		$result = $object->fetch($id);
		if ($result < 0)
		{
			dol_print_error($db);
		}

		/*
		 * Affichage fiche
		 */
		if ($action <> 'edit' && $action <> 're-edit')
        
		{


			print '<table class="border" width="100%">';

        	$calcproductsunique=$object->nb_different_products();
			$calcproducts=$object->nb_products();

	        // Total nb of different products
	        print '<tr><td>'.$langs->trans("NumberOfDifferentProducts").'</td><td colspan="3">';
	        print empty($calcproductsunique['nb'])?'0':$calcproductsunique['nb'];
	        print "</td></tr>";

			// Nb of products
			print '<tr><td>'.$langs->trans("NumberOfProducts").'</td><td colspan="3">';
			print empty($calcproducts['nb'])?'0':$calcproducts['nb'];
			print "</td></tr>";


			// Last movement
			$sql = "SELECT max(m.datem) as datem";
			$sql .= " FROM ".MAIN_DB_PREFIX."stock_mouvement as m";
			$sql .= " WHERE m.fk_entrepot = '".$object->id."'";
			$resqlbis = $db->query($sql);
			if ($resqlbis)
			{
				$obj = $db->fetch_object($resqlbis);
				$lastmovementdate=$db->jdate($obj->datem);
			}
			else
			{
				dol_print_error($db);
			}
			print '<tr><td>'.$langs->trans("LastMovement").'</td><td colspan="3">';
			if ($lastmovementdate)
			{
			    print dol_print_date($lastmovementdate,'dayhour').' ';
			}
			else
			{
			     print $langs->trans("None");
			}
			print "</td></tr>";

			print "</table>";

			print '</div>';




?>







                                <div class="container">
                                <h2>Stock Actual</h2>
                
                                    <table class="table table-striped table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                            <th>Producto</th>
                                            <th>Etiqueta</th>
                                            <th>Unidades</th>
                                            </tr>
                                        </thead>
                                            <tbody>



                                            <tr>
                                                <td>John</td>
                                                <td>Doe</td>
                                            </tr>

                                            </tbody>
                                    </table>

                                <hr>    

                                <h4>Cantidad Comprobantes   - 26</h4>   
                                <h4>Monto Total   <strong>$3.568,50</strong></h4>
                                </div>



<?php




			/* ************************************************************************** */
			/*                                                                            */
			/* Affichage de la liste des produits de l'entrepot                           */
			/*                                                                            */
			/* ************************************************************************** */
			print '<br> <br><br><br><br><br>';

			print '<table class="noborder" width="100%">';
			print "<tr class=\"liste_titre\">";
			print_liste_field_titre($langs->trans("Product"),"", "p.ref","&amp;id=".$id,"","",$sortfield,$sortorder);
			print_liste_field_titre($langs->trans("Label"),"", "p.label","&amp;id=".$id,"","",$sortfield,$sortorder);
            print_liste_field_titre($langs->trans("Units"),"", "ps.reel","&amp;id=".$id,"",'align="right"',$sortfield,$sortorder);
            print_liste_field_titre($langs->trans("AverageUnitPricePMPShort"),"", "ps.pmp","&amp;id=".$id,"",'align="right"',$sortfield,$sortorder);
			print_liste_field_titre($langs->trans("EstimatedStockValueShort"),"", "","&amp;id=".$id,"",'align="right"',$sortfield,$sortorder);
            if (empty($conf->global->PRODUIT_MULTIPRICES)) print_liste_field_titre($langs->trans("SellPriceMin"),"", "p.price","&amp;id=".$id,"",'align="right"',$sortfield,$sortorder);
            if (empty($conf->global->PRODUIT_MULTIPRICES)) print_liste_field_titre($langs->trans("EstimatedStockValueSellShort"),"", "","&amp;id=".$id,"",'align="right"',$sortfield,$sortorder);
			if ($user->rights->stock->mouvement->creer) print_liste_field_titre('');
			if ($user->rights->stock->creer)            print_liste_field_titre('');
			print "</tr>\n";

			$totalunit=0;
			$totalvalue=$totalvaluesell=0;

			$sql = "SELECT p.rowid as rowid, p.ref, p.label as produit, p.fk_product_type as type, p.pmp as ppmp, p.price, p.price_ttc, p.entity,";
			$sql.= " ps.pmp, ps.reel as value";
			$sql.= " FROM ".MAIN_DB_PREFIX."product_stock as ps, ".MAIN_DB_PREFIX."product as p";
			$sql.= " WHERE ps.fk_product = p.rowid";
			$sql.= " AND ps.reel <> 0";	// We do not show if stock is 0 (no product in this warehouse)
			$sql.= " AND ps.fk_entrepot = ".$object->id;
			$sql.= $db->order($sortfield,$sortorder);


			dol_syslog('List products', LOG_DEBUG);
			$resql = $db->query($sql);
			if ($resql)
			{
				$num = $db->num_rows($resql);
				$i = 0;
				$var=True;
				while ($i < $num)
				{
					$objp = $db->fetch_object($resql);

					// Multilangs
					if (! empty($conf->global->MAIN_MULTILANGS)) // si l'option est active
					{
						$sql = "SELECT label";
						$sql.= " FROM ".MAIN_DB_PREFIX."product_lang";
						$sql.= " WHERE fk_product=".$objp->rowid;
						$sql.= " AND lang='". $langs->getDefaultLang() ."'";
						$sql.= " LIMIT 1";

						$result = $db->query($sql);
						if ($result)
						{
							$objtp = $db->fetch_object($result);
							if ($objtp->label != '') $objp->produit = $objtp->label;
						}
					}

					$var=!$var;
					//print '<td>'.dol_print_date($objp->datem).'</td>';
					print "<tr ".$bc[$var].">";
					print "<td>";
					$productstatic->id=$objp->rowid;
                    $productstatic->ref = $objp->ref;
                    $productstatic->label = $objp->produit;
					$productstatic->type=$objp->type;
					$productstatic->entity=$objp->entity;
					print $productstatic->getNomUrl(1,'stock',16);
					print '</td>';
					print '<td>'.$objp->produit.'</td>';

					print '<td align="right">'.$objp->value.'</td>';
					$totalunit+=$objp->value;

                    // Price buy PMP
					print '<td align="right">'.price(price2num($objp->ppmp,'MU')).'</td>';
                    // Total PMP
					print '<td align="right">'.price(price2num($objp->ppmp*$objp->value,'MT')).'</td>';
					$totalvalue+=price2num($objp->ppmp*$objp->value,'MT');

                    // Price sell min
                    if (empty($conf->global->PRODUIT_MULTIPRICES))
                    {
                        $pricemin=$objp->price;
                        print '<td align="right">';
                        print price(price2num($pricemin,'MU'),1);
                        print '</td>';
                        // Total sell min
                        print '<td align="right">';
                        print price(price2num($pricemin*$objp->value,'MT'),1);
                        print '</td>';
                    }
                    $totalvaluesell+=price2num($pricemin*$objp->value,'MT');


					print "</tr>";
					$i++;
				}
				$db->free($resql);

				print '<tr class="liste_total"><td class="liste_total" colspan="2">'.$langs->trans("Total").'</td>';
				print '<td class="liste_total" align="right">'.$totalunit.'</td>';
				print '<td class="liste_total">&nbsp;</td>';
                print '<td class="liste_total" align="right">'.price(price2num($totalvalue,'MT')).'</td>';
                if (empty($conf->global->PRODUIT_MULTIPRICES))
                {
                    print '<td class="liste_total">&nbsp;</td>';
                    print '<td class="liste_total" align="right">'.price(price2num($totalvaluesell,'MT')).'</td>';
                }
                print '<td class="liste_total">&nbsp;</td>';
				print '<td class="liste_total">&nbsp;</td>';
				print '</tr>';

			}
			else
			{
				dol_print_error($db);
			}
			print "</table>\n";
		}



		
	}



$db->close();

?>



















                                <!--fin de bloque tab1-->



                                </div>
                            <div id="menu1" class="tab-pane fade">


                            <!--  inicio del bloque de tab 2-->

                                <div class="container">
                                <h2>Stock Actual</h2>
                
                                    <table class="table table-striped table-responsive table-bordered text-center">
                                        <thead>
                                            <tr>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Monto</th>
                                            <th>Accion</th>

                                            </tr>
                                        </thead>
                                            <tbody>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>                                            
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>                                                                                        
                                            </tbody>
                                    </table>
                                </div>




                            <!--fin de bloque tab 2-->


                            <h3>Aqio van los comprobantes</h3>
                            <p>Some content in menu 1.</p>
                            </div>

                        </div>



                </div>



                <div class="container">

                    <input class="btn btn-primary btn-block" name="sbmtConnexion" type="submit" value="Imprimir cierre" />
                </div>

<hr>



        </body>

    <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>

    <script type="text/javascript" src="javascript/list_clients.js"></script>
</html>





