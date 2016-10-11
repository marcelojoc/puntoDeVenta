<?php

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Facturation.class.php';
// validar acceso
/*
var consulta trae el nombre de la funcion que ejecuta

dato  trae el parametro
*/


$consulta = GETPOST("consulta", "alpha");
$dato     = GETPOST("dato", "alpha");


$codVendedor= $_SESSION['codVendedor'];


// $consulta = $_GET["consulta"];
// $dato     =  $_GET["dato"];





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



        case 'get_valProduct':                        // consulta datos del producto  y si tiene tabla de descuentos la devuelve esta de otro modo devuelve una nula

        if(!isset($_SESSION['serObjFacturation'])){  // si no hay variable de sesion para el objeto facturacion  la creo


                 $obj_facturation = new Facturation();

        }else{                                       // si ya existe. la desserializo  y la elimino, debo recrear el objeto

                $obj_facturation = unserialize($_SESSION['serObjFacturation']);
                unset ($_SESSION['serObjFacturation']);

        }




            $sql= '
                SELECT p.rowid, p.ref, p.price, p.tva_tx, p.recuperableonly, 
                ps.reel FROM llx_product AS p 
                LEFT JOIN llx_product_stock AS ps 
                ON p.rowid = ps.fk_product 
                AND ps.fk_entrepot = '. $_SESSION['CASHDESK_ID_WAREHOUSE'] .' 
                WHERE p.entity IN (1) 
                AND p.rowid ='. $dato 
            ;

           
             $resql=$db->query($sql);  //Aqui tengo los datos del producto seleccionado



             $num = $db->num_rows($resql);
             // si devuelve producto  entro al proceso
             if ($num){

                     /*
                      debo traer el producto, precio, Stock y otros datos
                      verificar que enga una lista de descuentos y trarla en ese caso
                      instanciar el objeto facturation  y asignar los valores a cada parametro
                     
                     */
                        $tabSql= 'SELECT * FROM `llx_desc` WHERE `fk_product` = '.$dato ;  // crea la consulta la lista de descuentos

                        $re_tabsql= $db->query($tabSql);  // consulto  si existen descuentos 

                       $tabla = $db->num_rows($re_tabsql);

                       $matriz_desc= array();

                        if($tabla >0){    // si hay tabla devolver la matriz de descuentos cargada

                                    $i=0;

                                while ($i < $tabla)  // recorrer el fech y guradarlo en un arreglo 
                                {
                                        $obj = $db->fetch_object($re_tabsql);
                                        if ($obj)
                                        {
                                                // You can use here results
                                                $matriz_desc[]= array(
                                                                        'list' => $i,
                                                                        'min'=>$obj->linf,
                                                                        'max'=> $obj->lsup,
                                                                        'descuento'=> $obj->descuento
                                                );
                                                
                                        }
                                        $i++;
                                }
                   

                        }else{           // si no hya tabla devolver matriz basica de descuento (valor de compra normal)

                                $matriz_desc= array(

                                                'list1'     => "lista basica",
                                                'min'       => "0",
                                                'max'       => "max",
                                                'descuento'=> '0.30'

                                );

                        }




                // luego de cargar la lista de descuentos hay que traer el producto y setear el objeto












               $respuesta=array('tabla_desc' => $matriz_desc,
               
               'producto' => [250, 30,223],
               ) ;














             }else{

                        $respuesta= array(
                                        'error'       => "los datos son invalidos"
                        );
             }











             break;


	case 'get_client':     // sin definicion




              break;



        case 'get_products':   // busca productos del almacen del vendedor y su Stock  y lo envia para cargar el select html

                $sql='SELECT p.rowid, p.ref, p.label, p.tva_tx, p.fk_product_type, ps.reel 
                FROM llx_product AS p 
                LEFT JOIN llx_product_stock AS ps ON p.rowid = ps.fk_product 
                AND ps.fk_entrepot = '. $_SESSION['CASHDESK_ID_WAREHOUSE'] .' WHERE p.entity IN (1) 
                AND p.tosell = 1 
                AND p.fk_product_type = 0  ORDER BY label';

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
                                                                        'id_product'=> $obj->rowid,
                                                                        'nom_product'=>$obj->label,
                                                                        'stock_product'=> $obj->reel
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



        case 'getAll':        // busca clientes de acuerdo al codigo asociado al vendedor


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

