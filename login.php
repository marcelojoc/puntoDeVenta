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


top_htmlhead('','',0,0,'',''); // header carga de librerias y estilos
?>

<body>
    
    

<?php if ($err) print dol_escape_htmltag($err)."<br><br>\n"; ?>
    <form id="frmLogin" method="POST" action="index_verif.php">
	<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />

<table>

	<tr>
		<td class="label1">LOGIN</td>
		<td><input name="txtUsername" class="texte_login" type="text" value="<?php echo $usertxt; ?>" /></td>
	</tr>
	<tr>
		<td class="label1"><?php echo $langs->trans("Password"); ?></td>
		<td><input name="pwdPassword" class="texte_login" type="password" value="" /></td>
	</tr>



 <?php


//echo($conf->global->PRODUIT_MULTIPRICES);  //opcion para saber si esta activo o no multiprecio

//var_dump($_SESSION);

// ?>


 <br>

<div align="center"><span class="bouton_login"><input class="button" name="sbmtConnexion" type="submit" value=<?php echo $langs->trans("Connection"); ?> /></span></div>

</form>
</fieldset>



</body>
</html>