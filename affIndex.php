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

// var_dump($_SESSION);
//top_htmlhead('','',0,0,'',''); // cargo encabezados
?>

<!--<script type="text/javascript" src="javascript/facturation1.js"></script>
<script type="text/javascript" src="javascript/dhtml.js"></script>
<script type="text/javascript" src="javascript/keypad.js"></script>-->

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Punto de venta</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">



<body>



<div class="container">
  

<div class="row">


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Punto de Venta</a>
    </div>
    <!--<ul class="nav navbar-nav navbar-right">
      <li><a href="#"><span class="glyphicon glyphicon glyphicon-user" aria-hidden="true"></span></a></li> 
      <li><a href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a></li> 
      
      <li><a href="#"><span class="glyphicon  glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li> 


    </ul>-->
  </div>
</nav>


</div>
  

  <div class="row">



    <form action="affIndex.php" metod="POST" name="formProduct">

        <div class="col-xs-5">
            <div class="input-group">
                <input id="txtref" name="txtref" class="form-control" placeholder="ref" type="text"/>
                <span class="input-group-addon"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>

            </div>

        <!--<input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md">-->
        </div>
        <div class="col-xs-7 ">
        
            <select class="form-control input-md" name="selectProduct" id="selectProduct" onchange="get_valProduct()" >

            </select>
        </div>

    </form>

  </div>




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

                    <td> <input id ='hiddenpUnit'type="hidden" value="">
                    <input id="txtPunit" name="txtPunit" type="text" placeholder="precio" class="form-control input-md" disabled></td>
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
                        <input id="appendedtext" name="appendedtext" class="form-control" placeholder="desc" type="text">
                        <span class="input-group-addon">%</span>
                        </div>
                        
                    </td>
                    <td><input id="textinput" name="textinput" type="text" placeholder="$$" class="form-control input-md" disabled></td>
                    <td>        <select class="form-control input-md">
                                <option value="1">qwqw</option>
                                <option value="2">wewe</option>
                                <option value="3">rerer</option>

                            </select>
        </td>
                </tr>
                
                </tbody>
            </table>



        </div>




            <!--boton de agregar producto-->

            <div class="row">

                <div class="col-xs-12">

            <button type="button" class="btn btn-success btn-block">Añadir</button>
                </div>

                
            </div>


<hr>


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
                        <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md" disabled></td>
                        <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md" ></td>
                        <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md" disabled></td>
                    </tr>
                    
                    </tbody>

        </table>
    </div>
    </div>






    <div class="panel panel-default">
        <div class="panel-heading text-center">Medio de Pago</div>
            <div class="panel-body">


                <div class="row">

                    <div class="col-xs-6">

                    <button type="button" class="btn btn-success btn-block">Efectivo</button>

                    </div>

                    <div class="col-xs-6">

                        
                    </div>

              </div>

        </div>
  </div>





</div>




 <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>

     <script type="text/javascript" src="javascript/ptv_principal.js"></script>
  </body>
</html>