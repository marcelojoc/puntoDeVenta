<?php


/**
 *	\file       htdocs/cashdesk/validation.php
 *	\ingroup    cashdesk
 *	\brief      validation.php
 */

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';
$form=new Form($db);

// Affichage des templates
require ('tpl/validation1.tpl.php');

