<?php
include '../main.inc.php';
//require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';

// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}



//  $obj_facturation= unserialize($_SESSION['serObjFacturation']);


$page=GETPOST('menutpl','alpha');
$fid = GETPOST('facid', 'alpha');
$mensaje = GETPOST('mesg', 'alpha');
$redireccion = GETPOST('newVenta', 'alpha');

echo($redireccion);
    

if(isset($redireccion) && $redireccion== 'Nueva Venta' ){


unset ($_SESSION['poscart']);
unset ($_SESSION['CASHDESK_ID_THIRDPARTY']);
unset ($_SESSION['serObjFacturation']);

    header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php');

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

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">



<body>

<br>
    <div class="container">


        <div class="panel panel-default">
        <div class="panel-heading text-center">Venta Terminada</div>
         <form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id= "formSelClient" />   
<?php   

    // valido que lleguen los datos correctos si la factura esta bien 

    if($page == 'validation_ok'  && isset( $fid )){
?>

            <div class="panel-body">
                
                
            <p><a class="btn btn-info btn-block" href="<?php echo DOL_URL_ROOT ?>/compta/facture.php?action=builddoc&facid=<?php echo $_GET['facid']; ?>" target="_blank"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Ver Factura</a></p>
            <br>
            <p><a class="btn btn-info btn-block" href="<?php echo DOL_URL_ROOT ?>/compta/facture.php?facid=<?php echo $_GET['facid']; ?>&action=presend&mode=init" target="_blank"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Enviar por Email</a></p>
            <br>
            <p><a class="btn btn-info btn-block" href="#" onclick="Javascript: popupTicket(); return(false);" disabled="disabled"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> <?php echo $langs->trans("PrintTicket"); ?></a></p>
            <br>



                <input class="btn btn-success btn-block" type="submit" id="newVenta" name="newVenta"  value="Nueva Venta">

            </div> <!-- cierre de panel-body -->

            </form>      <!-- fin del form -->  

<?php 
    }else{

        if( isset( $fid ) && isset( $mesg )){

?>


            <div class="panel-body ">
                
                

                <p> Error en la creacion de la factura</p>


                <input class="btn btn-success btn-block" type="submit" id="newVenta" name="newVenta" value="Nueva Venta">


            </div> <!-- cierre de panel-body -->



<?php
        }
?>

<?php 
    }
?>

        </div> <!-- cierre panel -default -->
    </div>  <!-- container terminado-->

</body>


<script type="text/javascript">

	function popupTicket()
	{
		largeur = 600;
		hauteur = 500;
		opt = 'width='+largeur+', height='+hauteur+', left='+(screen.width - largeur)/2+', top='+(screen.height-hauteur)/2+'';
		window.open('validation_ticket.php?facid=<?php echo $_GET['facid']; ?>', '<?php echo $langs->trans('PrintTicket') ?>', opt);
	}

	

</script>

</html>