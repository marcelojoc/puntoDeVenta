<?php
include '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/reporte.class.php';

$langs->load("admin");
$langs->load("cashdesk");

$stock_print=array();
$stock_total=array();
$comprobante_print=[];


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
$hoy =date("Y-m-d");

// creo una instancia de reporte.. asigno datos de usuario
$reporte=new Reporte($db, $_SESSION['uid'], $hoy, $_SESSION['CASHDESK_ID_BANKACCOUNT_CASH'] );


// $pibe= $reporte->get_monto_caja();
// print $reporte->get_monto_caja()['total'] ;




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
                                <h3>Stock Actual</h3>
                
                                    <table class="table table-striped table-responsive table-bordered ">
                                        <thead>
                                            <tr>
                                            <th>ref</th>
                                            <th>Producto</th>
                                            <th>Unidades actuales</th>
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

                    // cargo los valores del Stock en el array
                    array_push($stock_print,[  'id'=>(string)$objp->rowid,
                                    'ref'=>(string)$objp->ref,
                                    'nombre'=>(string)$objp->produit,
                                    'tipo'=>(string)$objp->type,
                                    'entity'=>(string)$objp->entity,
                                    'cantidad'=>(string)$objp->value                
                                    ]);



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


?>
                                            <!--aqui termina la tabla-->
                                      </tbody>
                                    </table>

                                <hr>    



<h3>Productos vendidos</h3>
<table class="table table-striped table-responsive table-bordered ">
<thead>
<tr>
<th>ref</th>
<th>Producto</th>
<th>Unidades Vendidas</th>
</tr>
</thead>
<tbody>



<tr><td>1</td>
<td>Speed Unlimited 250 ml x 24 latas</td>
<td class="text-center">233</td>

</tr>

</tbody>
</table>
                                    <hr>

                                    <h4>Monto Total caja  <strong>$ <?php echo $reporte->get_monto_caja()['total']; ?> </strong></h4>

                                    <h4>Monto Total comprobantes  <strong>$ <?php echo $reporte->get_monto_comprobantes()['total'];  ?> </strong></h4>

                                    <h4>Cantidad de Comprobantes  <strong><?php echo $reporte->cantidad_comprobantes()['comprobantes'];  ?> </strong></h4>


                                </div>




                                </div>

                                <!--fin de bloque tab1-->



                               
                            <div id="menu1" class="tab-pane fade">


                            <!--  inicio del bloque de tab 2-->
                                <br>
                                <div class="container">




                                    <?php

                                    //cargo los comprobantes del vendedor y fecha de hoy
                                    $sql_f= "SELECT f.rowid, f.facnumber, 
                                                    f.total , f.datef, 
                                                    f.fk_soc, s.nom, s.code_client
                                                    FROM `llx_facture` AS f   
                                                    INNER JOIN llx_societe AS s 
                                                    ON f.fk_soc = s.rowid 
                                                    WHERE  f.datef = '". $hoy ."' AND f.fk_user_author = ". $_SESSION['uid'] ;





                                    $resql=$db->query($sql_f);

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




                                    print '<div class="col-xs-4">';

                                    print '<h4>Comprobante</h4>';
                                            
                                    print '<p>Cliente:   <strong> '.$obj->nom.' </strong></p>';
                                    print '<p>Codigo:   <strong>'. $obj->code_client.' </strong></p>';

                                    print'<p>Monto:   <strong> $ '. round($obj->total,2).' </strong></p>';


                                    print '</div>';

                                    // aqui guardo en la sesion los clientes que compraron

                                    $detalle_cliente[]=$obj;

                                    ?>

                                    
                                    <div class="col-xs-8">

                                        
                                            <h4>Detalle</h4>
                                                <table class="table table-striped table-responsive table-bordered text-center">
                                                    <thead>
                                                        <tr>
                                                        <th>Fecha</th>
                                                        <th>producto</th>
                                                        <th>cantidad</th>

                                                        </tr>
                                                    </thead>


                                                        <tbody>

                                    <?php   

                                    // consulta para traer el detalle de cada comprobante
                                    $sql_d= "SELECT d.rowid, d.description, d.qty FROM llx_facturedet  AS d WHERE fk_facture = ".$obj->rowid;


                                    $resp=$db->query($sql_d);
                                        if ($resp)
                                        {
                                            $cont = $db->num_rows($resp);

                                            if ($cont)
                                            {
                                                
                                                //foreach ($objeto as $dato)
                                                for($j = 1; $j<= $cont ; $j++)
                                                {   
                                                    $dato= $db->fetch_object($resp);
                                                    
                                                        print'<tr>';
                                                        print '<td>'. $obj->datef.'</td>';
                                                        print '<td>'. $dato->description .'</td>';
                                                        print '<td>'. $dato->qty .'</td>';
                                                        print'</tr>';

                                                }


                                            }

                                        }


                                    ?>

                                                        </tbody>

                                                    </table>
                                        <br>
                                    </div>



                                    <?php

                                                                        }
                                                                        $i++;
                                                                }
                                                        }
                                                    }else{

                                                            $respuesta = 'hay un error en la conexion';
                                                    }

var_dump($reporte->get_cantidad_unidades());

// $datos= $reporte->detalle_comprobante(101);


//  foreach($datos as $d){

//         echo($d->description);
//  }

                                                    $db->close();


                                    ?>

                                    <br>
                                    

                                </div>  <!--fin de bloque container-->

                                <hr>
                                  

                           </div><!--fin de bloque tab 2-->

                        

                </div>



                <div class="container">

                    <a class="btn btn-primary btn-block" href="reporteCierre.php" >Imprimir cierre<a/>
                </div>

                <hr>

    </body>

    <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>

    <script type="text/javascript" src="javascript/list_clients.js"></script>
</html>








<?php

$_SESSION['stock_print']= $stock_print;

$_SESSION['detalle_cliente']= $detalle_cliente;


// var_dump($_SESSION['stock_total']);


?>