<html><!--se puede incluir html en un php-->
<head>
<title>
Formulario
</title>
</head>
<body>
<form name="prueba" action="PRUEBA.PHP" method="POST"><!--generamos el formulario, se va a recargar por eso el action es "formulario2.php"-->
Nombre<input name="nombre" type="text" ><br />
ContraseÃ±a 



        <select  id="selCliente" name"selCliente">
                    <option value="value1">Value 1</option>
        <option value="value2">Value 2</option>
                    <option value="value1">Value 1</option>
        <option value="value2">Value 2</option>
                            <option value="value1">Value 1</option>
        <option value="value2">Value 2</option>
        </select>



<input type="submit" name="btn_envio" value="Entrar">
</form>
 
 
</body>
 
</html>
<?php

var_dump($_POST);
if (isset($_POST['btn_envio'])){//valida que se haya presionado enviar y generado los Ã­ndices en el Arreglo $_POST con las variables del formulario
    if (empty($_POST['nombre'])||empty($_POST['contra'])){//valida que en los formularios se haya escrito algo
        echo "debes llenar todos los <a href='formulario.htm'>campos</a> ";
        } else{
            echo "Bienvenido ".$_POST['nombre']." tu contraseÃ±a es:". md5($_POST['contra']);}
            }else {
                echo "debes llenar el formulario";
                }
?>