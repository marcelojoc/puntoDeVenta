<!--<form method="POST" name="mailform" id="mailform" enctype="multipart/form-data" action="http://localhost/dolibar_local/htdocs/compta/facture.php?id=42#formmail">
<input style="display:none" type="submit" id="sendmail" name="sendmail"><input type="hidden" name="token" value="ee491c3018491a01c2ce04db69e5e566"><input type="hidden" name="trackid" value="inv42"><a id="formmail" name="formmail"></a><input type="hidden" id="langsmodels" name="langsmodels" value="es_AR">
<input type="hidden" id="action" name="action" value="send">
<input type="hidden" id="models" name="models" value="facture_send">
<input type="hidden" id="models_id" name="models_id" value="">
<input type="hidden" id="facid" name="facid" value="42">
<input type="hidden" id="returnurl" name="returnurl" value="/dolibar_local/htdocs/compta/facture.php?id=42">
<div style="padding: 3px 0 3px 0">
Seleccione una plantilla de e-mail: <select name="modelmailselected" disabled="disabled"><option value="none" disabled="disabled">Sin plantilla definida para este tipo de e-mail</option></select> &nbsp; <input class="button" type="submit" value="Use" name="modelselected" disabled="disabled" id="modelselected"> &nbsp; </div><table class="border" width="100%">
<input type="hidden" id="fromname" name="fromname" value="ALAN WILIAM DAVILA ROMERO"><input type="hidden" id="frommail" name="frommail" value="soporte@tmsgroup.com"><tbody><tr><td width="180">Remitente</td><td><a href="/dolibar_local/htdocs/user/card.php?id=8" class="classfortooltip"><div class="inline-block valignmiddle"><img src="/dolibar_local/htdocs/theme/eldy/img/object_user.png" border="0" alt="" title="" style="padding-right: 3px;" class="classfortooltip"></div><div class="inline-block valignmiddle">ALAN WILIAM DAVILA ROMERO</div></a> &lt;soporte@tmsgroup.com&gt;</td></tr>

<tr><td width="180"><div class="inline-block"><div class="inline-block" style="padding: 0px; padding-right: 3px !important;">Destinatario(s)</div><div class="classfortooltip inline-block inline-block" style="padding: 0px; padding: 0px; padding-right: 3px !important;"><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="" style="vertical-align: middle; cursor: help"></div></div></td><td><input size="30" id="sendto" name="sendto" value=""></td></tr>
<tr><td width="180"><div class="inline-block"><div class="inline-block" style="padding: 0px; padding-right: 3px !important;">Copia a</div><div class="classfortooltip inline-block inline-block" style="padding: 0px; padding: 0px; padding-right: 3px !important;"><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="" style="vertical-align: middle; cursor: help"></div></div></td><td><input size="30" id="sendtocc" name="sendtocc" value=""></td></tr>
<tr><td width="180">Acuse de recibo</td><td><select class="flat" id="deliveryreceipt" name="deliveryreceipt">
<option value="1">Sí</option>
<option value="0" selected="">No</option>
</select>
</td></tr>
<tr><td width="180">Asunto del e-mail</td><td><input type="text" size="60" id="subject" name="subject" value="EnvÃ­o de la factura FA1610-0038"></td></tr>
<tr><td width="180">Archivo</td><td><input type="hidden" class="removedfilehidden" name="removedfile" value="">
<script type="text/javascript" language="javascript">jQuery(document).ready(function () {    jQuery(".removedfile").click(function() {        jQuery(".removedfilehidden").val(jQuery(this).val());    });})</script>
<div id="attachfile_0"><img src="/dolibar_local/htdocs/theme/common/mime/pdf.png" border="0" alt="" title="Mime type: pdf"> FA1610-0038.pdf <input type="image" style="border: 0px;" src="/dolibar_local/htdocs/theme/eldy/img/delete.png" value="1" class="removedfile" id="removedfile_0" name="removedfile_0"><br></div><input type="file" class="flat" id="addedfile" name="addedfile" value="Enviar archivo"> <input type="submit" class="button" id="addfile" name="addfile" value="Adjuntar este archivo"></td></tr>
<tr><td width="180" valign="top">Mensaje</td><td><textarea id="message" name="message" rows="8" style="margin-top: 2px; width: 95%; visibility: hidden; display: none;" class="flat">Le adjuntamos la factura FA1610-0038&lt;br&gt;
</textarea></td></tr>
<tr><td align="center" colspan="2"><div class="center"><input class="button" type="submit" id="sendmail" name="sendmail" value="Enviar e-mail" onclick="if (document.mailform.addedfile.value != '') { alert('Un archivo ha sido seleccionado para adjuntarlo, pero aún no se ha subido. Haga clic en \'Adjuntar este archivo\' para ello.'); return false; } else { return true; }"> &nbsp; &nbsp; <input class="button" type="submit" id="cancel" name="cancel" value="Anular"></div></td></tr>
</tbody></table>
</form>-->









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
    <div class="panel-heading">Resumen</div>
    <div class="panel-body">


        

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

                        echo ('<td class="resume_label">'.$langs->trans("DateEcheance").'</td><td>'.$obj_facturation->paiementLe().'</td>');

                    } else {

                        echo ('<td class="resume_label">'.$langs->trans("Received").'</td><td>'.price(price2num($obj_facturation->montantEncaisse(),'MT'),0,$langs,0,0,-1,$conf->currency).'</td>');

                    }

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
            
                <input class="btn btn-success btn-block" type="submit" name="btnValider" value="Validar Factura" /><br>    
        </td>
        </tr>
        </form>
        <tr>
            <td colspan="2">
                
                            <form id="frmValidation"  method="post" action="validation_verif.php?action=crear_remito">
                            <input class="btn btn-info btn-block" type="submit" name="btnValider" value="Generar Remito" /><br>

                        
                        
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







