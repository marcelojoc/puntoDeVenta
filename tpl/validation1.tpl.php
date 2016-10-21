<?php
$obj_facturation = unserialize($_SESSION['serObjFacturation']);
//unset ($_SESSION['serObjFacturation']);
$langs->load("main");
$langs->load("bills");

// Object $form must de defined
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Punto de venta</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">
   

<body>

	<div class="container">
		
	<br>

<div class="panel panel-default">
  <div class="panel-heading"><?php echo $langs->trans("Summary"); ?></div>
  <div class="panel-body">


		<table class="table_resume">

			<tr><td class="resume_label">Factura</td><td><?php  echo $obj_facturation->numInvoice(); ?></td></tr>
			<tr><td class="resume_label">Total_ht</td><td><?php echo price(price2num($obj_facturation->prixTotalHt(),'MT'),0,$langs,0,0,-1,$conf->currency); ?></td></tr>
			<?php
				// Affichage de la tva par taux
				if ( $obj_facturation->montantTva() ) {

					echo ('<tr><td class="resume_label">'.$langs->trans("VAT").'</td><td>'.price(price2num($obj_facturation->montantTva(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td></tr>');

				}
				else
				{

					echo ('<tr><td class="resume_label">'.$langs->trans("VAT").'</td><td>'.$langs->trans("NoVAT").'</td></tr>');

				}
			?>
			<tr><td class="resume_label"><?php echo $langs->trans("TotalTTC"); ?> </td><td><?php echo price(price2num($obj_facturation->prixTotalTtc(),'MT'),0,$langs,0,0,-1,$conf->currency); ?></td></tr>
			<tr><td class="resume_label"><?php echo $langs->trans("PaymentMode"); ?> </td><td>
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
			</td></tr>

			<?php
				// Affichage des infos en fonction du mode de paiement
				if ( $obj_facturation->getsetPaymentMode() == 'DIF' ) {

					echo ('<tr><td class="resume_label">'.$langs->trans("DateEcheance").'</td><td>'.$obj_facturation->paiementLe().'</td></tr>');

				} else {

					echo ('<tr><td class="resume_label">'.$langs->trans("Received").'</td><td>'.price(price2num($obj_facturation->montantEncaisse(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td></tr>');

				}

				// Affichage du montant rendu (reglement en especes)
				if ( $obj_facturation->montantRendu() ) {

					echo ('<tr><td class="resume_label">'.$langs->trans("Change").'</td><td>'.price(price2num($obj_facturation->montantRendu(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td></tr>');

				}

			?>

		</table>

		<form id="frmValidation" class="formulaire2" method="post" action="validation_verif.php?action=valide_facture">
			<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
			<p class="note_label">
				<?php
					echo $langs->trans("BankToPay"). "<br>";
					$form->select_comptes($selected,'cashdeskbank',0,$filtre);
				?>
			</p>
			<p class="note_label"><?php echo $langs->trans("Notes"); ?><br><textarea class="textarea_note" name="txtaNotes"></textarea></p>

			<div class="center">
				
				<input class="button" type="submit" name="btnValider" value="<?php echo $langs->trans("ValidateInvoice"); ?>" /><br>

				<input class="button" type="submit" name="btnValider" value="Generar Remito" /><br>

			<br><a class="lien1" href="affIndex.php?menutpl=facturation"><?php echo $langs->trans("RestartSelling"); ?></a>
			</div>
		</form>






  </div>
</div>


	</div>

 </body>


</html>
