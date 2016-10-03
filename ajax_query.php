<?php

require_once '../main.inc.php';

// validar acceso
/*
var consulta trae el nombre de la funcion que ejecuta

dato  trae el parametro
*/


$consulta = GETPOST("consulta", "alpha");
$dato     = GETPOST("dato", "alpha");


$codVendedor= $_SESSION['codVendedor'];


// $resql=$db->query("select * from llx_societe where rowid = 116");

//  if ($resql)
//  {
//          $num = $db->num_rows($resql);
//          $i = 0;
//          if ($num)
//          {
//                  while ($i < $num)
//                  {
//                          $obj = $db->fetch_object($resql);
//                          if ($obj)
//                          {
//                                  // You can use here results
//                                  $respuesta.= $obj->nom;
//                                  //print $obj->name_alias;
//                          }
//                          $i++;
//                  }
//          }
//  }else{

// 	 $respuesta = 'hay un error en la conexion';
//  }

$respuesta=null;

 switch ($consulta)
{

	default:

		$redirection = DOL_URL_ROOT.'/cashdesk/affIndex.php?menutpl=validation';
		break;


	case 'get_client':


    break;



    case 'getAll':


        // busca clientes de a cuerdo al codigo asociado al vendedor

        $sql="
        
            SELECT  llx_societe.code_client, 
            llx_societe.rowid , 
            llx_societe.nom

            FROM    llx_societe, llx_societe_extrafields
            WHERE   llx_societe_extrafields.vendedor = $codVendedor
            AND     llx_societe.rowid = llx_societe_extrafields.fk_object";
           // AND     code_client LIKE '".$dato."%'";

        $resql=$db->query($sql);

            if ($resql)
            {
                    $num = $db->num_rows($resql);
                    $i = 0;
                    if ($num)
                    {
                            while ($i < $num)
                            {
                                    $obj = $db->fetch_object($resql);
                                    if ($obj)
                                    {
                                            // You can use here results
                                            $respuesta[]= array(
                                                                'id_cliente'=> $obj->rowid,
                                                                'cod_cliente'=>$obj->code_client,
                                                                'nombre'=> $obj->nom
                                            );
                                            //print $obj->name_alias;
                                    }
                                    $i++;
                            }
                    }
            }else{

                $respuesta = 'hay un error en la conexion';
            }


    break;
}


echo json_encode($respuesta);

