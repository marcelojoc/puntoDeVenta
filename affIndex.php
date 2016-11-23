<?php
include '../main.inc.php';
//require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
date_default_timezone_set ('America/Argentina/Buenos_Aires');
// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}


//  $obj_facturation= unserialize($_SESSION['serObjFacturation']);

	$company=new Societe($db);
	$company->fetch($_SESSION["CASHDESK_ID_THIRDPARTY"]);


    if(!isset($_SESSION['CASHDESK_ID_THIRDPARTY'])){  // si entra y no hay cliente asignaod vuelve al form de seleccion

    $redirection='select_client.php';
    header('Location: '.$redirection);
    }

    //var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Punto de venta </title>
        
        <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet">
        <!-- bootstrap-datepicker -->
        <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">



<body>



<div class="container">
  

<nav class="navbar navbar-default ">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Vendedor <small><?php  echo($_SESSION['firstname']);  ?> </small> </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


      <ul class="nav navbar-nav navbar-right">


<?php   
          
            $tab=array();                       //  declaro un arreglo para almacenar lo que hay en el carrito
            $tab = $_SESSION['poscart'];        // asigno lo que hay en el carrito

            $tab_size=count($tab); 


?>
        <li><a href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" data-toggle="modal" data-target="#myModal" ><span class="badge success"><?php echo($tab_size); ?></span></span></a></li>
        <li><a href="deconnexion.php?action=new"  name="link_Nueva" >Nueva Venta</a></li>
        <li><a href="deconnexion.php?action=salir" name="link_Salir" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></li>



      </ul>


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
 

  <div class="row">



    <form action="affIndex.php" metod="POST" name="formProduct" autocomplete="off">


        <div class="col-xs-12 ">
        
            <select class="form-control input-md" name="selectProduct" id="selectProduct" onchange="get_valProduct()" >

            </select>
        </div>

    </form>

  </div>



<form id="frmQte"  method="post" action="facturation_verif.php?action=ajout_article" autocomplete="off"  onsubmit="return valNums($('#txtcantidad'))" >
        <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
        <div class="row">


            <table class="table table-striped">
                <thead class="success">
                <tr>
                    <th>Cantidad</th>
                    <th>Stock</th>
                    <th>P. Unitario</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input id="txtcantidad" name="txtcantidad" onkeyup="calculos(this.value);"type="text" placeholder="cantidad" class="form-control input-md"></td>
                    <td><input id="txtstock" name="txtstock" type="text" placeholder="Stock" class="form-control input-md" disabled></td>

                    <td> <input id ='hiddenpUnit'type="hidden" value="" >
                    <input id="txtPunit" name="txtPunit" type="text" placeholder="precio"  class="form-control input-md" disabled></td>
                </tr>
                
                
                </tbody>


                <thead class="thead-default">
                    <tr>
                    <th>Descuento</th>
                    <th>Base. Inmp</th>
                    <th>Tasa IVA</th>
                    </tr>
                </thead>

                <tbody>
                <tr>
                    <td>
                        
                        <div class="input-group">
                        <input id="txtdesc" name="txtdesc" class="form-control" onkeyup="calcularTotal();" placeholder="desc" type="text" >
                        <span class="input-group-addon">%</span>
                        </div>
                        
                    </td>
                    <td><input id="txtbase" name="txtbase" type="text"  class="form-control input-md" value="$0.00" disabled></td>
                    <td>        <select class="form-control input-md">
                                <option value="1">0</option>


                            </select>
        </td>
                </tr>
                
                </tbody>
            </table>



        </div>




            <!--boton de agregar producto-->

            <div class="row">

                <div class="col-xs-12">

            
            <input class="btn btn-success btn-block" type="submit" id="sbmtEnvoyer" value="Añadir"  >
                </div>

                
            </div>

</form>




<hr>

<!--onsubmit="javascript: return verifReglement()"-->
<form id="frmDifference"  method="post"  action="validation_verif.php?action=valide_achat" onsubmit="return verifSubmit();"  autocomplete="off">


<input type="hidden" name="hdnChoix" value="">
    <div class="panel panel-default">
    <div class="panel-heading text-center">Importe</div>
    <div class="panel-body">
        
        <table class="table table-striped">
                    <thead class="success">
                    <tr>
                        <th>Total</th>
                        <th>Recibido</th>
                        <th>Cambio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input id ='hiddentxttotal' name="hiddentxttotal" type="hidden" value="" >
                            <input id="txttotal" name="txttotal" type="text" placeholder="cantidad" class="form-control input-md" disabled>
                        </td>
                        <td><input id="txtRecibido" name="txtRecibido" type="text" placeholder="cantidad" class="form-control input-md" onkeyup="calculoCuenta();" ></td>
                        <td>
                            <input id ='hiddentxtVuelto' name="hiddentxtVuelto" type="hidden" value="" >
                            <input id="txtVuelto" name="txtVuelto" type="text" placeholder="cantidad" class="form-control input-md" disabled>
                        </td>
                    </tr>
                    
                    </tbody>

        </table>
    </div>
    </div>





<?php

    if($tab_size > 0){
?>
    <div class="panel panel-default">
        <div class="panel-heading text-center">Medio de Pago</div>
            <div class="panel-body">


                <div class="row">

                    <div class="col-xs-12">

                    
                    <input class="btn btn-success btn-block" type="submit" name="btnEfectivo" onclick="verifClic('ESP');" id="btnEfectivo" value="Efectivo" disabled>


                    </div>

              </div>

              <hr>
              <div class="row">

                    <div class="col-xs-12">

                            <div class="col-xs-6">

                                <input class="btn btn-success btn-block" type="submit" onclick="verifClic('DIF');" name="btnModeReglement" id="btnModeReglement" value="Cuenta Cte"/>

                            </div>

                            <div class="col-xs-6">


                                <div class="input-group date ">
                                <input id ='date_now' name="date_now" type="hidden" value="<?php echo(date("d/m/Y")); ?>">
                                <input type="text" class="form-control" id ='date_comp'><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"  pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])/(0[1-9]|1[012])/[0-9]{4}
"></i></span>
                                </div>
                            </div>

                        
                    </div>


              </div>


        </div>
  </div>
<?php
    }

?>


</form>   <!-- CIERRO EL FORMULARIO DE ENVIO DE PAGO -->


</div>










<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ventas a <?php echo ($company->name_alias);  ?></h4>
      </div>
      <div class="modal-body">
        

<?php 

var_dump($_SESSION);
if ($tab_size <= 0) {
    
    
    
    print '
        <div class="panel panel-default">
        <div class="panel-body">

        <address>
        <strong>No hay articulos</strong><br>
        </address>
        </div>
        </div>
        ';





//         <div class="panel panel-default">
//   <div class="panel-heading">Panel heading without title</div>
//   <div class="panel-body">
//     Panel content
//   </div>
// </div>

}
else
{


$total_valor=0;

for ($i=0;$i < $tab_size;$i++)
    {
        echo ('<div class="panel panel-default">');

        echo('<div class="panel-heading">
        
        
        <p class="panel-title pull-left">
            '.$tab[$i]['label'].'

        </p>

        <a id= "idcar_'.$i.'" href ="facturation_verif.php?action=suppr_article&suppr_id='.$tab[$i]['id'].'"   class="btn btn-danger btn-xs pull-right"  data-producto="'.$tab[$i]['fk_article'].'" data-cantidad="'.$tab[$i]['qte'].'" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
        <div class="clearfix"></div>
        </div>');

        if ( $tab[$i]['remise_percent'] > 0 ) {

            $remise_percent = ' -'.$tab[$i]['remise_percent'].'%';

        } else {

            $remise_percent = '';

        }

        $remise = $tab[$i]['remise'];


        echo('
        <div class="panel-body">
         <address>


           <strong>'.$tab[$i]['label'].'</strong><br>

        '
        
            .$tab[$i]['qte'].' x '.price2num($tab[$i]['price'], 'MT').$remise_percent.' = '.price(price2num($tab[$i]['total_ht'], 'MT'),0,$langs,0,0,-1,$conf->currency).' '.$langs->trans("HT").' ('.price(price2num($tab[$i]['total_ttc'], 'MT'),0,$langs,0,0,-1,$conf->currency).' '.$langs->trans("TTC").')
        
        <br>
        </address>
        </div>

        ');


        $total_valor+=$tab[$i]['total_ht'];

        echo('</div>');

    }


}

echo('<input id ="campototal" type="hidden" value="'.$total_valor.'">');
echo ('<p>Total :  $ '.$total_valor. '</p>');


?>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>


<?php 

?>



    <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
    <script type="text/javascript" src="javascript/moment.min.js"></script>
     <script type="text/javascript"  src="javascript/bootstrap-datepicker.min.js"></script> 
    <script type="text/javascript" src="javascript/ptv_principal.js"></script>


  </body>
</html>