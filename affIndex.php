<?php
include '../main.inc.php';
//require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';


// Test if user logged
if ( !$_SESSION['uid'] )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;
}
top_htmlhead('','',0,0,'',''); // cargo encabezados
?>

<script type="text/javascript" src="javascript/facturation1.js"></script>
<script type="text/javascript" src="javascript/dhtml.js"></script>
<script type="text/javascript" src="javascript/keypad.js"></script>

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



    <div class="col-xs-5">
            <div class="input-group">
      <input id="appendedtext" name="appendedtext" class="form-control" placeholder="ref" type="text">
      <span class="input-group-addon"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>

      
    </div>
    <!--<input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md">-->
    </div>
    <div class="col-xs-7 ">
    
        <select class="form-control input-md">
            <option value="">qwqw</option>
            <option value="">wewe</option>
            <option value="">rerer</option>
            <option value="">rtrt</option>
            <option value="">wwewe</option>
            <option value="">trtr</option>
            <option value="">wewew</option>
        </select>
    </div>

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
                    <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md"></td>
                    <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md" disabled></td>
                    <td><input id="textinput" name="textinput" type="text" placeholder="cantidad" class="form-control input-md" disabled></td>
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
                                <option value="4">rtrt</option>
                                <option value="5">wwewe</option>
                                <option value="6">trtr</option>
                                <option value="7">wewew</option>
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





    
</body>

</html>