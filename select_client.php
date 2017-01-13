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
              header('Location: '.DOL_URL_ROOT.'/cashdesk/affIndex.php');
          }else{

            unset($_POST);
            header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php?err=cliente incorrecto');

          }
        

      }else{  //  en caso de error vuelve a la seleccion del cliente

        unset($_POST);
        header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php');

      }

  }else{ //  si no hay datos post  cargo el form de busqueda de Clientes


    unset($_SESSION['serObjFacturation']); //borro sesion de facturacion y de carro de compras
    unset($_SESSION['poscart']);

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
   <link href="css/estilos.css" rel="stylesheet">




<body>


<div class="container-fluid">    



	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-default formcentro" >
				<!--<div class="panel-heading">
					<div class="panel-title text-center">Acceso TPV</div>
					
				</div>     -->

				<div style="padding-top:30px" class="panel-body" >

					<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
						
					<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id= "formSelClient" class="form-horizontal " role="form" autocomplete="off">

          
              <div class="col-sm-12 controls text-right">


              <a class="negro" href="deconnexion.php?action=salir" name="link_Salir" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

              </div>

              <div style="margin-bottom: 25px" class="input-group">
                <input type="hidden" id="hiddenCode"  name="hiddenCode" value="" >
                <input name="txtcodigo" type="text" id="txtcodigo" onkeyup="loadComponent(this.value)" class="form-control" placeholder="Codigo Cliente" required/>  

              </div>
							
              <div style="margin-bottom: 25px" class="input-group">
                <select  id="selCliente" name"selCliente" onclick ="asignarCodigo();" onchange ="asignarCodigo()" class="form-control" required>
                  <option value="0">--------------------</option>

                </select>
              </div>

							<div style="margin-top:10px" class="form-group">
								<!-- Button -->

<div class="col-sm-12">

    <div class="panel panel-default">
        
            <div class="panel-body">


                        <div class="text-left">
	                       
	                        <p><i class="glyphicon glyphicon-user"></i> Numero Cliente: 0039 333 12 68 347</p>
	                        <p><i class="glyphicon glyphicon-user"></i> Nombre: Andia_Agency</p>
	                        <p><i class="glyphicon glyphicon-user"></i> Direccion: Via Principe Amedeo 9, 10100, Torino, TO, Italy</p>
                        </div>

            </div>
    </div>
</div>


								<div class="col-sm-12 controls">
									
									
                   <input class="btn btn-success btn-block" name="sbmtConnexion" type="submit" value="Ingreso TPV" />

								</div>
							</div>

						</form> 




					</div>                     
				</div>  
	</div>

</div> 



      <!--<form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id= "formSelClient" />

      <input type="hidden" id="hiddenCode"  name="hiddenCode" value="" >

      Nombre<input name="txtcodigo" type="text" id="txtcodigo" onkeyup="loadComponent(this.value)"  required/><br />

          <select  id="selCliente" name"selCliente" onclick ="asignarCodigo();" required>

          </select>



      <input class="button" name="sbmtConnexion" type="submit" value="conexion" />
      </form>-->












</body>

    <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>

    <script type="text/javascript" src="javascript/list_clients.js"></script>
</html>



<?php
  }
?>


    
    






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





