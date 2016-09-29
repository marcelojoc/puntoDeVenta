<?php

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/html.formproduct.class.php';
// validar acceso


$form=new Form($db);

$search = GETPOST("code", "alpha");


echo($search);