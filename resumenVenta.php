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



                    <div class="container ">
                        
                        
                        <div class="col-xs-10"> 

                            <h3> Vendedor <?php echo($_SESSION['lastname']) ?></h3>

                        </div>
                         <div class="col-xs-2 text-right"> 
                            <br>
                            <a id="singlebutton" name="singlebutton" class="btn btn-default" href="select_client.php">Volver</a>

                        </div>                       

                    
                    
                    
                        

                    
                    
                    </div>





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


            $hoy=date("Y-m-d");
            $sqlTotal = "SELECT SUM(amount) AS total FROM llx_bank  WHERE llx_bank.fk_account = ". $_SESSION['CASHDESK_ID_BANKACCOUNT_CASH']."  AND llx_bank.datev BETWEEN '".$hoy."' AND '".$hoy."' LIMIT 1";
			$restotal = $db->query($sqlTotal);
            $num = $db->num_rows($restotal);
             // si devuelve producto  entro al proceso
             if ($num){

                        $obj = $db->fetch_object($restotal);
                        if ($obj)
                        {
                            
                            $total= round($obj->total,2);

                        }
             }
			
		}

		
	}





?>
                                            <!--aqui termina la tabla-->
                                      </tbody>
                                    </table>

                                <hr>    

                                <h4>Monto Total   <strong>$ <?php echo($total); ?> </strong></h4>
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


<?php
                $sql="SELECT b.rowid, b.dateo AS DO, b.datev 
                AS dv, b.amount, b.label, DATE_FORMAT(b.tms,'%d/%m/%Y a las %H:%i') AS tms, ba.rowid 
                AS bankid, ba.ref AS bankref, ba.label AS banklabel, s.rowid AS socid, s.nom AS thirdparty 
                FROM llx_bank_account AS ba, llx_bank AS b LEFT JOIN llx_bank_url AS bu1 ON bu1.fk_bank = b.rowid AND bu1.type='company' 
                LEFT JOIN llx_societe AS s ON bu1.url_id = s.rowid 
                LEFT JOIN llx_bank_url AS bu2 ON bu2.fk_bank = b.rowid AND bu2.type='payment_vat' 
                LEFT JOIN llx_tva AS t ON bu2.url_id = t.rowid 
                LEFT JOIN llx_bank_url AS bu3 ON bu3.fk_bank = b.rowid AND bu3.type='payment_salary' 
                LEFT JOIN llx_payment_salary AS sal ON bu3.url_id = sal.rowid WHERE b.fk_account=".$_SESSION['CASHDESK_ID_BANKACCOUNT_CASH']." 
                AND b.fk_account = ba.rowid 
                AND ba.entity IN (1) 
                AND (b.datev BETWEEN '".$hoy."' AND '".$hoy."') 
                ORDER BY b.datev ASC,b.datec ASC";

                $resql=$db->query($sql);

                if ($resql)
                {
                        $num = $db->num_rows($resql);
                        $i = 0;
                        if ($num)
                        {
                                while ($i < $num)
                                {
                                        $obj = $db->fetch_object($resql);
                                        if ($obj)
                                        {

                                            print '<tr>';
                                                print '<td>'.$obj->tms.'</td>';
                                                print '<td>'.$obj->thirdparty.'</td>';
                                                print '<td>'.round($obj->amount,2).'</td>';
                                                print '<td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>';
                                            print '</tr>';

                                        }
                                        $i++;
                                }
                        }
                }else{

                        $respuesta = 'hay un error en la conexion';
                }

                $db->close();

?>


                                                                                        
                                            </tbody>
                                    </table>



<!--codigo de tabla         -->












<!--codigo de tabla         -->



















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





