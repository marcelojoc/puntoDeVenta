



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




<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es">
<head>
<meta name="robots" content="noindex,nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="author" content="Dolibarr Development Team">
<link rel="shortcut icon" type="image/x-icon" href="/dolibar_local/htdocs/theme/eldy/img/favicon.ico"/>
<link rel="top" title="Inicio" href="/dolibar_local/htdocs">
<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
<link rel="author" title="Dolibarr Development Team" href="http://www.dolibarr.org">
<title>Pedido</title>
<!-- Includes CSS for JQuery (Ajax library) -->
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/includes/jquery/css/smoothness/jquery-ui.css?version=3.9.3">
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/includes/jquery/plugins/tiptip/tipTip.css?version=3.9.3">
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/includes/jquery/plugins/jnotify/jquery.jnotify-alt.min.css?version=3.9.3">
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/includes/jquery/plugins/select2/select2.css?version=3.9.3">
<!-- Includes CSS for Dolibarr theme -->
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/theme/eldy/style.css.php?lang=es_AR&amp;theme=eldy&amp;userid=1&amp;entity=1&amp;version=3.9.3">
<!-- Includes CSS added by module descuentos -->
<link rel="stylesheet" type="text/css" href="/dolibar_local/htdocs/descuentos/css/bootstrap.min.css">
<!-- Includes CSS added by module descuentos -->

<!-- Includes JS for CKEditor -->
<script type="text/javascript">var CKEDITOR_BASEPATH = '/dolibar_local/htdocs/includes/ckeditor/ckeditor/';
var ckeditorConfig = '/dolibar_local/htdocs/theme/eldy/ckeditor/config.js';
var ckeditorFilebrowserBrowseUrl = '/dolibar_local/htdocs/core/filemanagerdol/browser/default/browser.php?Connector=/dolibar_local/htdocs/core/filemanagerdol/connectors/php/connector.php';
var ckeditorFilebrowserImageBrowseUrl = '/dolibar_local/htdocs/core/filemanagerdol/browser/default/browser.php?Type=Image&Connector=/dolibar_local/htdocs/core/filemanagerdol/connectors/php/connector.php';
</script>

</head>


<h1></h1>


<?php  $datecommande =   date("m-d-Y");





echo($datecommande);

 ?>

<body id="mainbody">

<!-- Start top horizontal -->



<div id="id-container">
<!-- Begin id-left -->
<div class="side-nav"><div id="id-left">

<!-- Begin left menu -->
<div class="vmenu">


<!-- Show logo on menu -->


<!-- Begin SearchForm -->



<!-- End Help Block-->

</div>
<!-- End left menu -->

</div></div> <!-- end id-left -->
<!-- Begin right area -->
<div id="id-right">
<div class="fiche"> <!-- begin div class="fiche" -->

<table summary="" width="100%" border="0" class="notopnoleftnoright" style="margin-bottom: 2px;"><tr><td class="nobordernopadding hideonsmartphone" width="40" align="left" valign="middle"><img src="/dolibar_local/htdocs/theme/eldy/img/title_commercial.png" border="0" alt="" title="" id="pictotitle"></td><td class="nobordernopadding" valign="middle"><div class="titre">Crear pedido</div></td></tr></table>

<form name="crea_commande" action="prueba_remitos.php" method="POST">
<input type="hidden" name="token" value="d5daff44a7d85e8b6de2a560c4583bd6">
<input type="hidden" name="action" value="add"><input type="hidden" name="socid" value="118">
<input type="hidden" name="remise_percent" value="0">
<input type="hidden" name="origin" value=""><input type="hidden" name="originid" value="">

<div class="tabs" data-role="controlgroup" data-type="horizontal">
</div>

<div class="tabBar">
<table class="border" width="100%"><tr><td width="25%" class="fieldrequired">Ref.</td>
<td colspan="2">Borrador</td></tr><tr><td>Ref. cliente</td><td colspan="2">
    <input type="text" name="ref_client" value=""></td></tr><tr><td class="fieldrequired">Cliente</td>
    <td colspan="2"><a href="/dolibar_local/htdocs/societe/soc.php?socid=241" title="&lt;div width=&quot;100%&quot;&gt;&lt;u&gt;Mostrar tercero&lt;/u&gt;&lt;br&gt;&lt;b&gt;Nombre:&lt;/b&gt;  BARRERAS MIRIAM BEATRIZ (BARRERAS MIRIAM BEATRIZ)&lt;br&gt;&lt;b&gt;C&oacute;digo cliente:&lt;/b&gt; 1424&lt;/div&gt;" class="classfortooltip"><img src="/dolibar_local/htdocs/theme/eldy/img/object_company.png" border="0" alt="  BARRERAS MIRIAM BEATRIZ (BARRERAS MIRIAM BEATRIZ)&lt;br&gt;C&oacute;digo cliente" title="&lt;div width=&quot;100%&quot;&gt;&lt;u&gt;Mostrar tercero&lt;/u&gt;&lt;br&gt;Nombre" class="classfortooltip"></a> <a href="/dolibar_local/htdocs/societe/soc.php?socid=241" title="&lt;div width=&quot;100%&quot;&gt;&lt;u&gt;Mostrar tercero&lt;/u&gt;&lt;br&gt;&lt;b&gt;Nombre:&lt;/b&gt;  BARRERAS MIRIAM BEATRIZ (BARRERAS MIRIAM BEATRIZ)&lt;br&gt;&lt;b&gt;C&oacute;digo cliente:&lt;/b&gt; 1424&lt;/div&gt;" class="classfortooltip"> BARRERAS MIRIAM BEATRIZ (BARRERAS MIRIAM BEATRIZ)</a>
    
    <input type="hidden" name="socid" value="118"></td></tr>
<tr><td>Contacto por defecto</td><td colspan="2"><!-- JS CODE TO ENABLE select2 for id contactid -->
          <script type="text/javascript">
        	$(document).ready(function () {
        		$('#contactid').select2({
        		    dir: 'ltr',
        			width: 'resolve',		/* off or resolve */
					minimumInputLength: 0
				});
});
</script>
<select class="flat" id="contactid" name="contactid" data-role="none"><option value="0"></option><option value="-1" selected disabled>Ning&uacute;n contacto definido para este tercero</option></select></td></tr><tr><td>Descuentos</td><td colspan="2">Este cliente no tiene descuentos relativos por defecto. Este cliente no tiene m&aacute;s descuentos fijos disponibles.</td></tr><tr><td class="fieldrequired">Fecha</td><td colspan="2"><input id="re" name="re" type="text" size="9" maxlength="11" value="11/03/2016" onChange="dpChangeDay('re','MM/dd/yyyy'); "><button id="reButton" type="button" class="dpInvisibleButtons" onClick="showDP('/dolibar_local/htdocs/core/','re','MM/dd/yyyy','es_AR');"><img src="/dolibar_local/htdocs/theme/eldy/img/object_calendarday.png" border="0" alt="" title="Seleccione una fecha" class="datecallink"></button><input type="hidden" id="reday"   name="reday"   value="03">
<input type="hidden" id="remonth" name="remonth" value="11">
<input type="hidden" id="reyear"  name="reyear"  value="2016">


 <button class="dpInvisibleButtons datenowlink" id="reButtonNow" type="button" name="_useless" value="now" onClick="jQuery('#re').val('11/03/2016');jQuery('#reday').val('03');jQuery('#remonth').val('11');jQuery('#reyear').val('2016');">Ahora</button> </td></tr><tr><td>Fecha de entrega</td><td colspan="2"><input id="liv_" name="liv_" type="text" size="9" maxlength="11" value="" onChange="dpChangeDay('liv_','MM/dd/yyyy'); "><button id="liv_Button" type="button" class="dpInvisibleButtons" onClick="showDP('/dolibar_local/htdocs/core/','liv_','MM/dd/yyyy','es_AR');"><img src="/dolibar_local/htdocs/theme/eldy/img/object_calendarday.png" border="0" alt="" title="Seleccione una fecha" class="datecallink"></button><input type="hidden" id="liv_day"   name="liv_day"   value="">
<input type="hidden" id="liv_month" name="liv_month" value="">
<input type="hidden" id="liv_year"  name="liv_year"  value="">


 <button class="dpInvisibleButtons datenowlink" id="liv_ButtonNow" type="button" name="_useless" value="now" onClick="jQuery('#liv_').val('11/03/2016');jQuery('#liv_day').val('03');jQuery('#liv_month').val('11');jQuery('#liv_year').val('2016');">Ahora</button> </td></tr><tr><td class="nowrap">Condiciones de pago</td><td colspan="2"><select class="flat" name="cond_reglement_id"><option value="0">&nbsp;</option><option value="1">A la recepci&oacute;n</option><option value="2">30 d&iacute;as</option><option value="3">30 d&iacute;as fin de mes</option><option value="4">60 d&iacute;as</option><option value="5">60 d&iacute;as a fin de mes</option><option value="6">Pedido</option><option value="7">A la entrega</option><option value="8">50/50</option></select><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="Puede cambiar estos valores en el men&uacute; configuraci&oacute;n-&gt;diccionarios" class="hideonsmartphone"></td></tr><tr><td>Forma de pago</td><td colspan="2"><select id="selectmode_reglement_id" class="flat selectpaymenttypes" name="mode_reglement_id"><option value="0" selected>&nbsp;</option><option value="7">Cheque</option><option value="3">Domiciliación</option><option value="4">Efectivo</option><option value="6">Tarjeta</option><option value="2">Transferencia</option></select><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="Puede cambiar estos valores en el men&uacute; configuraci&oacute;n-&gt;diccionarios" class="hideonsmartphone"></td></tr><tr><td>Tiempo de entrega</td><td colspan="2"><select class="flat" name="availability_id"><option value="0">&nbsp;</option><option value="2">1 semana</option><option value="3">2 semanas</option><option value="4">3 semanas</option><option value="1">Inmediata</option></select><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="Puede cambiar estos valores en el men&uacute; configuraci&oacute;n-&gt;diccionarios" class="hideonsmartphone"></td></tr><tr><td>M&eacute;todo de env&iacute;o</td><td colspan="2"><select id="selectshipping_method_id" class="flat selectshippingmethod" name="shipping_method_id"><option value="-1">&nbsp;</option><option value="1">Recogido por el cliente</option><option value="2">Transportista</option></select><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="Puede cambiar estos valores en el men&uacute; configuraci&oacute;n-&gt;diccionarios" class="hideonsmartphone"></td></tr><tr><td>Origen</td><td colspan="2"><select class="flat" name="demand_reason_id"><option value="0" selected>&nbsp;</option><option value="8">Boca a boca</option><option value="7">Campa&ntilde;a E-Mailing</option><option value="2">Campa&ntilde;a correo</option><option value="4">Campa&ntilde;a fax</option><option value="3">Campa&ntilde;a telef&oacute;nica</option><option value="5">Contacto comercial</option><option value="6">Contacto tienda</option><option value="10">Empleado</option><option value="1">Internet</option><option value="11">Patrocinador</option><option value="9">Socio</option></select><img src="/dolibar_local/htdocs/theme/eldy/img/info.png" border="0" alt="" title="Puede cambiar estos valores en el men&uacute; configuraci&oacute;n-&gt;diccionarios" class="hideonsmartphone"></td></tr><tr><td>Modelo</td><td colspan="2"><select id="model" class="flat model" name="model" ><option value="einstein" selected>einstein</option>
</select></td></tr><tr><td class="border" valign="top">Nota (p&uacute;blica)</td><td valign="top" colspan="2">
    
    
    
    <textarea id="note_public" name="note_public" rows="3" style="margin-top: 2px; width: 90%" class="flat"></textarea>
    
    
    
    
    
    </td></tr></table>
</div>
<div class="center"><input type="submit" class="button" name="bouton" value="Crear borrador"></div></form>

</div> <!-- End div class="fiche" -->
</div> <!-- End div id-right -->

<!-- Common footer for private page -->

<script type="text/javascript" language="javascript">jQuery(document).ready(function() {
<!-- If page_y set, we set scollbar with it -->
page_y=getParameterByName('page_y', 0);if (page_y > 0) $('html, body').scrollTop(page_y);<!-- Set handler to add page_y param on some a href links -->
jQuery(".reposition").click(function() {
    	           var page_y = $(document).scrollTop();
    	           /* alert(page_y); */
    	           this.href=this.href+'&page_y='+page_y;
    	           });
});
</script>
</div> <!-- End div id-container -->
<!-- JS CODE TO ENABLE select2 for id searchselectcombo -->
	    	<script type="text/javascript">
	    	$(document).ready(function () {
    	      
    	        var saveRemoteData = [];
    	    
                $(".searchselectcombo").select2({
			    	ajax: {
				    	dir: "ltr",
				    	url: "/dolibar_local/htdocs/core/ajax/selectsearchbox.php",
				    	dataType: 'json',
				    	delay: 250,
				    	data: function (searchTerm, pageNumber, context) {
				    		return {
						    	q: searchTerm, // search term
				    			page: pageNumber
				    		};
			    		},
			    		results: function (remoteData, pageNumber, query) {
			    			console.log(remoteData);
				    	    saveRemoteData = remoteData;
				    	    /* format json result for select2 */
				    	    result = []
				    	    $.each( remoteData, function( key, value ) {
				    	       result.push({id: key, text: value.text});
                            });
			    			//return {results:[{id:'none', text:'aa'}, {id:'rrr', text:'Red'},{id:'bbb', text:'Search a into projects'}], more:false}
			    			return {results: result, more:false}
    					},
			    		/*processResults: function (data, page) {
			    			// parse the results into the format expected by Select2.
			    			// since we are using custom formatting functions we do not need to
			    			// alter the remote JSON data
			    			console.log(data);
			    			return {
			    				results: data.items
			    			};
			    		},*/
			    		cache: true
			    	},
			        dropdownCssClass: "css-searchselectcombo", 
				    placeholder: "Buscar",
			    	escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			    	minimumInputLength: 1,
			        formatResult: function(result, container, query, escapeMarkup) {
                        return escapeMarkup(result.text);
                    }
			    });
			    
                
                $(".searchselectcombo").change(function() { 
			    	var selected = $(".searchselectcombo").select2("val");
			        $(".searchselectcombo").select2("val","");  /* reset visible combo value */
    			    $.each( saveRemoteData, function( key, value ) {
    				        if (key == selected)
    			            {
    			                 console.log("Do a redirect into selectArrayAjax to "+value.url)
    			                 location.assign(value.url);
    			            }
                    });
    			});
    			
    	   });
	       </script>
<!-- JS CODE TO ENABLE tipTip on all object with class classfortooltip -->
<script type="text/javascript">
            	jQuery(document).ready(function () {
            		jQuery(".classfortooltip").tipTip({maxWidth: "400px", edgeOffset: 10, delay: 50, fadeIn: 50, fadeOut: 50});
            	});
            </script>

<!-- A div to allow dialog popup -->
<div id="dialogforpopup" style="display: none;"></div>
</body>
</html>










