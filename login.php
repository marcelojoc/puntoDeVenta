<?php
require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';

$langs->load("admin");
$langs->load("cashdesk");


// Test if user logged
if ( $_SESSION['uid'] > 0 )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php');
	exit;
}

$usertxt=GETPOST('user','',1);
$err=GETPOST("err");

if (usertxt){

    $usertxt=GETPOST('user','',1);

}else{

	$usertxt=$_SESSION['dol_login'];
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
   <link href="css/estilos.css" rel="stylesheet">




<body>
    
    

<?php if ($err) print dol_escape_htmltag($err)."<br><br>\n"; ?>




<div class="container">    
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-default formcentro" >
				<!--<div class="panel-heading">
					<div class="panel-title text-center">Acceso TPV</div>
					
				</div>     -->

				<div style="padding-top:30px" class="panel-body" >

					<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
						
					<form id="frmLogin" method="POST" action="index_verif.php" class="form-horizontal " role="form">
								
						<div style="margin-bottom: 25px" class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input id="txtUsername" type="text" class="form-control" name="txtUsername" value="" placeholder="Usuario">                                        
								</div>
							
						<div style="margin-bottom: 25px" class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
									<input id="pwdPassword" type="password" class="form-control" name="pwdPassword" placeholder="password">
								</div>

							<div style="margin-top:10px" class="form-group">
								<!-- Button -->

								<div class="col-sm-12 controls">
									
									<input class="btn btn-success btn-block" name="sbmtConnexion" type="submit" value="conexion" />

								</div>
							</div>

						</form>     

					</div>                     
				</div>  
	</div>

</div> 












</body>
</html>