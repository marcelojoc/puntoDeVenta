<?php


/**
 *	\file       htdocs/cashdesk/deconnexion.php
 *	\ingroup    cashdesk
 *	\brief      Manage deconnexion for point of sale module
 */

//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Uncomment creates pb to relogon after a disconnect
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');

require_once '../main.inc.php';

// This destroy tag that say "Point of Sale session is on".



$obj_facturation = unserialize($_SESSION['serObjFacturation']);
//unset ($_SESSION['serObjFacturation']);

$action =GETPOST('action');



switch ($action)
{
	case 'new':       //si viene sin datos get  elimina la sesion... login principal

        unset($_SESSION['CASHDESK_ID_THIRDPARTY']);
        unset($_SESSION['poscart']);
        unset ($_SESSION['serObjFacturation']);
        header('Location: '.DOL_URL_ROOT.'/cashdesk/select_client.php');
        
		
	    break;


	case 'salir':

        session_destroy();
        header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');

        break;

    default:

        session_destroy();
        header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');

    break;    
}