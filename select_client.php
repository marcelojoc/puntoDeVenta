<?php
include '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';


$langs->load("admin");
$langs->load("cashdesk");

var_dump($_SESSION);

var_dump($_POST);

echo GETPOST('selCliente','',1);


// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}

top_htmlhead('','',0,0,'','');
?>

<body>
    
    

<form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id= "formSelClient" >

    
    
Nombre<input name="txtcodigo" type="text" id="txtcodigo"><br />



  


        <select  id="selCliente" name"selCliente">
                    <option value="value1">Value 1</option>
        <option value="value2">Value 2</option>

        </select>
  

  
  <input class="button" name="sbmtConnexion" type="submit" value="conexion" />
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

<script type="text/javascript" src="javascript/list_clients.js"></script>
</html>