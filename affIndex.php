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



 $obj_facturation= unserialize($_SESSION['serObjFacturation']);


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
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-theme.min.css" rel="stylesheet">

<style>

.spinner {
  margin: 100px auto 0;
  width: 70px;
  text-align: center;
  display:float;
}

.spinner > div {
  width: 18px;
  height: 18px;
  background-color: #333;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.spinner .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}

</style>

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
        
            <select class="form-control input-md" name="selectProduct" id="selectProduct" >

            </select>
        </div>

    </form>

  </div>


<div class="spinner">
  <div class="bounce1"></div>
  <div class="bounce2"></div>
  <div class="bounce3"></div>
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
                    <td><input id="txtcantidad" name="txtcantidad" type="text" placeholder="cantidad" class="form-control input-md"></td>
                    <td><input id="txtstock" name="txtstock" type="text" placeholder="Stock" class="form-control input-md" disabled></td>
                    <td><input id="txtPunit" name="txtPunit" type="text" placeholder="precio" class="form-control input-md" disabled></td>
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