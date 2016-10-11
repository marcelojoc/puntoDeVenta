<?php

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/keypad.php';



// Test if already logged
if ( $_SESSION['uid'] <= 0 )
{
	header('Location: index.php');
	exit;
}



// $thirdpartyid = (GETPOST('selCliente') > 0)?GETPOST('selCliente'):$conf->global->CASHDESK_ID_THIRDPARTY;
// $_SESSION['CASHDESK_ID_THIRDPARTY'] = $thirdpartyid ;

$langs->load("cashdesk");



/*
 * View
 */

//header("Content-type: text/html; charset=UTF-8");
//header("Content-type: text/html; charset=".$conf->file->character_set_client);

$arrayofjs=array();
$arrayofcss=array('/cashdesk/css/style.css');

top_htmlhead($head,$langs->trans("CashDesk"),0,0,$arrayofjs,$arrayofcss);

print '<body>'."\n";

if (!empty($error))
{
	print $error;
	print '</body></html>';
	exit;
}

print '<div class="conteneur">'."\n";
print '<div class="conteneur_img_gauche">'."\n";
print '<div class="conteneur_img_droite">'."\n";

print '<h1 class="entete"><span>POINT OF SALE</span></h1>'."\n";

print '<div class="menu_principal">'."\n";
include_once 'tpl/menu.tpl.php';
print '</div>'."\n";

print '<div class="contenu">'."\n";
include_once 'affContenu.php';
print '</div>'."\n";

include_once 'affPied.php';

print '</div></div></div>'."\n";
print '</body></html>'."\n";

var_dump( unserialize($_SESSION['serObjFacturation']));
