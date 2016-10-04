<?php
include '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';

$langs->load("admin");
$langs->load("cashdesk");

// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}

/*

1)comprobar si hay post del boton y si el campo codigo no esta vacio al igual que el hidden

2) verificar el cliente  si es el mismo del valor que viene por post

3) llenar el paramentro de session que falta y redireccionar a el punto de venta

*/

  if($_POST && isset($_POST['sbmtConnexion'])){  // valido que los datos esten llegando por post

  $hidden=$_POST['hiddenCode'];
  $codCliente= $_POST['txtcodigo'];

      if ($hidden!= "" &&  $hidden > 0 ){  // compruebo el campo hiden

        include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';  
        $company=new Societe($db);

          if( $company->fetch($hidden) ){    // compruebo que el valor exista y sea correcto

              $_SESSION["CASHDESK_ID_THIRDPARTY"]=$hidden;
              header('Location: '.DOL_URL_ROOT.'/cashdesk/affindex.php');
          }else{

            unset($_POST);
            header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php?err=cliente incorrecto');

          }
        

      }else{  //  en caso de error vuelve a la seleccion del cliente

        unset($_POST);
        header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php');

      }

  }else{ //  si no hay datos post  cargo el form de busqueda de Clientes

    top_htmlhead('','',0,0,'',''); // cargo encabezados

 ?>   





      <form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id= "formSelClient" >

      <input type="hidden" id="hiddenCode"  name="hiddenCode" value="" >

      Nombre<input name="txtcodigo" type="text" id="txtcodigo" onkeyup="loadComponent(this.value)"  required/><br />

          <select  id="selCliente" name"selCliente" onclick ="asignarCodigo();" required>

          </select>



      <input class="button" name="sbmtConnexion" type="submit" value="conexion" />
      </form>


</body>

<script type="text/javascript" src="javascript/list_clients.js"></script>
</html>



<?php
  }
?>

<body>
    
    






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





