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


<br>

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

			if ($lastmovementdate)
			{
			   print'<h5>Ultimo movimiento de Stock <strong>'.dol_print_date($lastmovementdate,'dayhour').'</strong></h5>';
			}
			else
			{
			     print $langs->trans("None");
			}


			




?>







                                <div class="container">
                                <h2>Stock Actual</h2>
                
                                    <table class="table table-striped table-responsive table-bordered ">
                                        <thead>
                                            <tr>
                                            <th>ref</th>
                                            <th>Producto</th>
                                            <th>Unidades</th>
                                            </tr>
                                        </thead>
                                            <tbody>


<?php


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




					$productstatic->id=$objp->rowid;
                    $productstatic->ref = $objp->ref;
                    $productstatic->label = $objp->produit;
					$productstatic->type=$objp->type;
					$productstatic->entity=$objp->entity;



print '
                                            <tr ><td>'.$productstatic->ref.'</td>
                                                <td>'.$productstatic->label.'</td>
                                                <td class="text-center">'.$objp->value.'</td>

                                            </tr>
';
					
					$totalunit+=$objp->value;

					$i++;
				}


				$db->free($resql);

				print '<tr class="liste_total"><td colspan="2">Total Unidades</td>';
				print '<td class="text-center">'.$totalunit.'</td>';


				print '</tr>';

			}
			else
			{
				dol_print_error($db);
			}
			
		}



		
	}



$db->close();

?>
                                            <!--aqui termina la tabla-->
                                            </tbody>
                                    </table>

                                <hr>    

                                <h4>Cantidad Comprobantes   - 26</h4>   
                                <h4>Monto Total   <strong>$3.568,50</strong></h4>
                                </div>


                                </div>

                                <!--fin de bloque tab1-->



                               
                            <div id="menu1" class="tab-pane fade">


                            <!--  inicio del bloque de tab 2-->

                                <div class="container">
                                <h2>Comprobantes</h2>
                
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





