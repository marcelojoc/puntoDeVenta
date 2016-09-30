<?php
require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';

$langs->load("admin");
$langs->load("cashdesk");

//var_dump($_SESSION);

// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}

top_htmlhead('','',0,0,'','');
?>

<body>
    
    

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="txtcodigo">Codigo</label>
    <input type="text" class="form-control" id="txtcodigo" placeholder="Codigo">
  </div>

  <div class="form-group">

    <label class="sr-only" for="selCliente">Cliente</label>
        <select class="form-control" id="selCliente">
            

        </select>
  </div>

  <button type="submit" class="btn btn-default">Sign in</button>
</form>


<?php

// $formproduct=new FormProduct($db);
// $form=new Form($db);
// print "<tr>";
// print '<td class="label1">'.$langs->trans("CashDeskThirdPartyForSell").'</td>';
// print '<td>';
// $disabled=0;
// $langs->load("companies");
// if (! empty($conf->global->CASHDESK_ID_THIRDPARTY)) $disabled=1; // If a particular third party is defined, we disable choice
// print $form->select_company(GETPOST('socid','int')?GETPOST('socid','int'):$conf->global->CASHDESK_ID_THIRDPARTY,'socid','s.client in (1,3)',!$disabled,$disabled,1);
// //  print '<input name="warehouse_id" class="texte_login" type="warehouse_id" value="" />';



//echo($conf->global->PRODUIT_MULTIPRICES);  //opcion para saber si esta activo o no multiprecio

//var_dump($_SESSION);

// ?>





</body>

<script type="text/javascript" src="javascript/dhtml.js"></script>
</html>