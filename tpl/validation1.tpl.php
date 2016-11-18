<?php
$obj_facturation = unserialize($_SESSION['serObjFacturation']);
//unset ($_SESSION['serObjFacturation']);
$langs->load("main");
$langs->load("bills");
    if(!isset($_SESSION['CASHDESK_ID_THIRDPARTY'])){  // si entra y no hay cliente asignaod vuelve al form de seleccion

    $redirection='select_client.php';
    header('Location: '.$redirection);
    }
// Object $form must de defined
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
    <title>Punto de venta</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">
   

<body>

	<div class="container">
		
	<br>





    <div class="panel panel-default">
    <div class="panel-heading">Resumen</div>
    <div class="panel-body">


        <?php //var_dump($_SESSION);?>


         <br>

         <?php //var_dump(unserialize($_SESSION['serObjFacturation']));?>

    <table class="table table-bordered table-responsive">



        <tbody>
        <tr>
            <td>Factura</td>
            <td> <?php echo $obj_facturation->numInvoice(); ?></td>

        </tr>
        <tr>

            <?php
                // Affichage de la tva par taux
                if ( $obj_facturation->montantTva() ) {

                    echo ('<td>IVA</td> <td>'.price(price2num($obj_facturation->montantTva(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td>');

                }
                else
                {

                    echo ('<td>IVA</td>  <td>'.$langs->trans("NoVAT").'</td>');

                }
            ?>
        </tr>

        <tr>
            <td>TOTAL</td>
            <td><?php echo price(price2num($obj_facturation->prixTotalTtc(),'MT'),0,$langs,0,0,-1,$conf->currency); ?></td>

        </tr>

            <tr>
            <td>Forma de pago</td>
            <td>
                
                
                        <?php
                switch ($obj_facturation->getSetPaymentMode())
                {
                    case 'ESP':
                        echo $langs->trans("Cash");
                        $filtre='courant=2';
                        if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]))
                            $selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"];
                        break;
                    case 'CB':
                        echo $langs->trans("CreditCard");
                        $filtre='courant=1';
                        if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]))
                            $selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CB"];
                        break;
                    case 'CHQ':
                        echo $langs->trans("Cheque");
                        $filtre='courant=1';
                        if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]))
                            $selected = $_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"];
                        break;
                    case 'DIF':
                        echo $langs->trans("Reported");
                        $filtre='courant=1 OR courant=2';
                        $selected='';
                        break;
                    default:
                        $filtre='courant=1 OR courant=2';
                        $selected='';
                }

                ?>

            </td>

        </tr>

        <tr>

                <?php
                    // Affichage des infos en fonction du mode de paiement
                    if ( $obj_facturation->getsetPaymentMode() == 'DIF' ) {

                        echo ('<td class="resume_label"> Fecha de Vencimiento </td><td>'.$obj_facturation->paiementLe().'</td>');

                    } else {

                        echo ('<td class="resume_label">'.$langs->trans("Received").'</td><td>'.price(price2num($obj_facturation->montantEncaisse(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td>');

                    }

				 ?>

        </tr>

		<tr>

			<?php
				// Affichage du montant rendu (reglement en especes)
				if ( $obj_facturation->montantRendu() ) {

					echo ('<td class="resume_label">'.$langs->trans("Change").'</td><td>'.price(price2num($obj_facturation->montantRendu(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td>');

				}

			?>

		</tr>

        <form id="frmValidation" class="" method="post" action="validation_verif.php?action=valide_facture">
        <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />



            <tr>
            <td>Nota</td>
            <td><p><textarea class="textarea_note" name="txtaNotes" rows="4" cols="30"></textarea></p></td>

        </tr>
        <tr>
        <td colspan="2">
            
                <input class="btn btn-success btn-block" type="submit" name="btnValider" value="Generar Comprobante" /><br>    
        </td>
        </tr>
        </form>
        <tr>
            <td colspan="2">
                
                            <form id="frmValidation"  method="post" action="validation_verif.php?action=crear_remito">
                            <input class="btn btn-info btn-block" type="submit" name="btnValider" value="Generar Remito"/ disabled><br>

                        
                        
                            </form>
                
            </td>
        </tr>


        <tr>
            <td colspan="2">
                
                <a class="btn btn-default btn-block" href="affIndex.php?menutpl=facturation" role="button" >Retomar la venta</a>   
                
            </td>
        </tr>

        </tbody>
    
    </table>


    
    </div> <!--cierre div panel body-->


    </div> <!--cierre div panel default-->
</div>
 </body>


</html>
