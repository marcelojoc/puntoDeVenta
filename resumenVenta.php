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
  

        <body>


                <div class="container">    



    <div class="container"><h3>Nombre del Vendedor</h3></div>





                        <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Resumen</a></li>
                        <li><a data-toggle="tab" href="#menu1">Comprobantes</a></li>

                        </ul>

                        <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                
                                <!--este es el bloque de tab1-->

                                <div class="container">
                                <h2>Stock Actual</h2>
                
                                    <table class="table table-striped table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                            <th>Producto</th>
                                            <th>cantidad</th>

                                            </tr>
                                        </thead>
                                            <tbody>
                                            <tr>
                                                <td>John</td>
                                                <td>Doe</td>
                                            </tr>
                                            <tr>
                                                <td>Mary</td>
                                                <td>Moe</td>
                                            </tr>
                                            <tr>
                                                <td>Mary</td>
                                                <td>Moe</td>
                                            </tr>
                                            <tr>
                                                <td>Mary</td>
                                                <td>Moe</td>
                                            </tr>

                                            </tbody>
                                    </table>


                                <hr>    


                                <h4>Cantidad Comprobantes   - 26</h4>   


                                <h4>Monto Total   <strong>$3.568,50</strong></h4>


                                </div>


                                <!--fin de bloque tab1-->



                                </div>
                            <div id="menu1" class="tab-pane fade">


                            <!--  inicio del bloque de tab 2-->

                                <div class="container">
                                <h2>Stock Actual</h2>
                
                                    <table class="table table-striped table-responsive table-bordered text-center">
                                        <thead>
                                            <tr>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Monto</th>
                                            <th>Accion</th>

                                            </tr>
                                        </thead>
                                            <tbody>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>                                            
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>
                                            <tr>
                                                <td>09:27</td>
                                                <td>Cliente cliente</td>
                                                <td>$400</td>
                                                <td><button type="button" class="btn btn-warning btn-xs">eliminar</button></td>


                                            </tr>                                                                                        
                                            </tbody>
                                    </table>
                                </div>




                            <!--fin de bloque tab 2-->


                            <h3>Aqio van los comprobantes</h3>
                            <p>Some content in menu 1.</p>
                            </div>

                        </div>



                </div>



                <div class="container">

                    <input class="btn btn-primary btn-block" name="sbmtConnexion" type="submit" value="Imprimir cierre" />
                </div>

<hr>



        </body>

    <script type="text/javascript" src="javascript/jquery-3.1.1.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>

    <script type="text/javascript" src="javascript/list_clients.js"></script>
</html>





