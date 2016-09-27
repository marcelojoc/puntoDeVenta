<?php
require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';

$langs->load("admin");
$langs->load("cashdesk");

// Test if user logged
if ( $_SESSION['uid'] > 0 )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/affIndex.php');
	exit;
}

$usertxt=GETPOST('user','',1);
$err=GETPOST("err");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    
    


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

	<tr>
		<td colspan="2">
		&nbsp;
		</td>
	</tr>

// <?php
// print "<tr>";
// print '<td class="label1">'.$langs->trans("CashDeskThirdPartyForSell").'</td>';
// print '<td>';
// $disabled=0;
// $langs->load("companies");
// if (! empty($conf->global->CASHDESK_ID_THIRDPARTY)) $disabled=1; // If a particular third party is defined, we disable choice
// print $form->select_company(GETPOST('socid','int')?GETPOST('socid','int'):$conf->global->CASHDESK_ID_THIRDPARTY,'socid','s.client in (1,3)',!$disabled,$disabled,1);
// //  print '<input name="warehouse_id" class="texte_login" type="warehouse_id" value="" />';


// print '</td>';
// print "</tr>\n";

// if (! empty($conf->stock->enabled) && empty($conf->global->CASHDESK_NO_DECREASE_STOCK))
// {
// 	$langs->load("stocks");
// 	print "<tr>";
// 	print '<td class="label1">'.$langs->trans("Warehouse").'</td>';
// 	print '<td>';
// 	$disabled=0;
// 	if ($conf->global->CASHDESK_ID_WAREHOUSE > 0) $disabled=1;	// If a particular stock is defined, we disable choice
// 	print $formproduct->selectWarehouses((GETPOST('warehouseid')?GETPOST('warehouseid','int'):(empty($conf->global->CASHDESK_ID_WAREHOUSE)?'ifone':$conf->global->CASHDESK_ID_WAREHOUSE)),'warehouseid','',!$disabled,$disabled);
// 	print '</td>';
// 	print "</tr>\n";
// }

// print "<tr>";
// print '<td class="label1">'.$langs->trans("CashDeskBankAccountForSell").'</td>';
// print '<td>';
// $defaultknown=0;
// if (! empty($conf->global->CASHDESK_ID_BANKACCOUNT_CASH) && $conf->global->CASHDESK_ID_BANKACCOUNT_CASH > 0) $defaultknown=1;	// If a particular stock is defined, we disable choice
// print $form->select_comptes(((GETPOST('bankid_cash') > 0)?GETPOST('bankid_cash'):$conf->global->CASHDESK_ID_BANKACCOUNT_CASH),'CASHDESK_ID_BANKACCOUNT_CASH',0,"courant=2",($defaultknown?0:2));
// print '</td>';
// print "</tr>\n";

// print "<tr>";
// print '<td class="label1">'.$langs->trans("CashDeskBankAccountForCheque").'</td>';
// print '<td>';
// $defaultknown=0;
// if (! empty($conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE) && $conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE > 0) $defaultknown=1;	// If a particular stock is defined, we disable choice
// print $form->select_comptes(((GETPOST('bankid_cheque') > 0)?GETPOST('bankid_cheque'):$conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE),'CASHDESK_ID_BANKACCOUNT_CHEQUE',0,"courant=1",($defaultknown?0:2));
// print '</td>';
// print "</tr>\n";

// print "<tr>";
// print '<td class="label1">'.$langs->trans("CashDeskBankAccountForCB").'</td>';
// print '<td>';
// $defaultknown=0;
// if (! empty($conf->global->CASHDESK_ID_BANKACCOUNT_CB) && $conf->global->CASHDESK_ID_BANKACCOUNT_CB > 0) $defaultknown=1;	// If a particular stock is defined, we disable choice
// print $form->select_comptes(((GETPOST('bankid_cb') > 0)?GETPOST('bankid_cb'):$conf->global->CASHDESK_ID_BANKACCOUNT_CB),'CASHDESK_ID_BANKACCOUNT_CB',0,"courant=1",($defaultknown?0:2));
// print '</td>';
// print "</tr>\n";

// ?>


// <br>

<div align="center"><span class="bouton_login"><input class="button" name="sbmtConnexion" type="submit" value=<?php echo $langs->trans("Connection"); ?> /></span></div>

</form>
</fieldset>



</body>
</html>