<?php

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';

// Test if user logged
if ( $_SESSION['uid'] > 0 )
{
	header('Location: '.DOL_URL_ROOT.'/cashdesk/affIndex.php');
	exit;
}else
{

	header('Location: '.DOL_URL_ROOT.'/cashdesk/login.php');
	exit;

}


