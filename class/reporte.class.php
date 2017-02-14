<?php
class Reporte {

	var $db;      // instancia de conexion
	var $vendedor;// id del vendedor
	var $hoy;       //fecha de hoy para la base de datos
    var $caja;      // caja asociada al vendedor


	function __construct($db, $vendedor, $hoy,$caja)
	{
		$this->db = $db;
        $this->vendedor = $vendedor;
        $this->hoy= $hoy;
        $this->caja= $caja;
		//$this->reponse(null);
	}


// este metodo devuelve el valor de movimientos de caja del dia 
    function get_monto_caja()
    {

        $sqlTotal = "SELECT SUM(amount) AS total FROM llx_bank  WHERE llx_bank.fk_account = ". $this->caja ."  AND llx_bank.datev BETWEEN '".$this->hoy."' AND '".$this->hoy."' LIMIT 1";
        
        $restotal = $this->db->query($sqlTotal);
        $num = $this->db->num_rows($restotal);
        // si devuelve producto  entro al proceso
        if ($num){

        $obj = $this->db->fetch_object($restotal);
            if ($obj)
            {
                
                $total= round($obj->total,2);

                // aqui guardo el valor total de ventas
                $stock_total=array('total'=> $total);

            }
        }

        return $stock_total;

    }


// este metodo devuelve el valor de todos los comprobantes del dia 

    function get_monto_comprobantes()  
    {

        $sqlTotal_comp = "
        
        SELECT SUM(total) AS total
        FROM `llx_facture` AS f   
        INNER JOIN llx_societe AS s 
        ON f.fk_soc = s.rowid 
        WHERE  f.datef = '".$this->hoy ."' AND f.fk_user_author =".$this->vendedor;
        
        $restotal = $this->db->query($sqlTotal_comp);
        $num = $this->db->num_rows($restotal);
        // si devuelve producto  entro al proceso
        if ($num)
        {

            $obj = $this->db->fetch_object($restotal);
            if ($obj)
            {
                
                $total= round($obj->total,2);
                // aqui guardo el valor total de ventas
                $comprobante_total=array('total'=> $total);
            }
        }

        return $comprobante_total;

    }



// devuelve las cantidades de cada producto vendidos
    function get_cantidad_unidades($desc= null)
    {

        $comprobantes= $this->get_comprobantes();  //  105,  106,  107, 108
        $productos   = $this->get_all_products();  // lista de productos 

        if($desc == null){
            $desc= 0;
        }

            if($comprobantes){

                    foreach ($productos as $producto)  // bucle iterador de comprobantes
                    
                    {

                        // un for each con los productos

                             $total_uni = 0;
                            foreach ($comprobantes as $num_comprobante)
                            {

                                    $val      = $this->cantidad_vendidas($producto->rowid, $num_comprobante->rowid, $desc );
                                    $total_uni =  $total_uni+ $val;

                            }
                            //cargo el arreglo con cada producto y su cantidad
                            $datos[]= ['ref'=> $producto->ref,
                            'label'=> $producto->label,
                            'cantidad'=> $total_uni
                            ];
                            

                    }

                        return $datos ;

            }







    }




    function cantidad_vendidas($id_producto, $id_detalle, $descuento = null)


    {



               $sql='SELECT SUM(qty) as total  FROM
                  llx_facturedet WHERE
                  fk_facture = '.$id_detalle.' AND
                   fk_product = '.$id_producto.' AND
                   remise_percent ='.$descuento ;

        $restotal = $this->db->query($sql);
        $num = $this->db->num_rows($restotal);
        // si devuelve producto  entro al proceso
        if ($num){

            $obj = $this->db->fetch_object($restotal);
            if ($obj)
            {
                
                $total= (int)$obj->total ;


            }
        }

        return  $total;

    }





// devuelve todos los productos en venta
    function get_all_products()
    {

        $productos= array();
        $sqlProduct= "SELECT rowid, ref, label FROM llx_product WHERE tosell =1";

        $resp= $this->db->query($sqlProduct);

        if ($resp)
        {
            $cont = $this->db->num_rows($resp);

            if ($cont)
            {
                //foreach ($objeto as $dato)
                for($j = 1; $j<= $cont ; $j++)
                {   
                    $dato= $this->db->fetch_object($resp);
                    

                        array_push($productos, $dato );
                }


            }

            return $productos;

        }


    }


// devuelve los comprobantes de hoy solo para el vendedor
function get_comprobantes(){

    $sql= "SELECT f.rowid, f.facnumber, 
                    f.total , f.datef, 
                    f.fk_soc, s.nom, s.code_client
                    FROM `llx_facture` AS f   
                    INNER JOIN llx_societe AS s 
                    ON f.fk_soc = s.rowid 
                    WHERE  f.datef = '". $this->hoy ."' AND f.fk_user_author = ". $this->vendedor ;

    $resp=$this->db->query($sql);
        if ($resp)
        {
            $cont = $this->db->num_rows($resp);

            if ($cont)
            {
                
                //foreach ($objeto as $dato)
                for($j = 1; $j<= $cont ; $j++)
                {   
                    $dato= $this->db->fetch_object($resp);

                        $data[]= $dato;

                }


            }

        }
    return $data;

}

    // esta funcion trae el detalle del comprobante indicado...
    function detalle_comprobante($id_comprobante)
    {

        $data;
        $sql_d= "SELECT d.rowid, d.description, d.qty FROM llx_facturedet  AS d WHERE fk_facture = ".$id_comprobante;

        $resp=$this->db->query($sql_d);
                if ($resp)
                {
                    $cont = $this->db->num_rows($resp);

                    if ($cont)
                    {
                        
                        //foreach ($objeto as $dato)
                        for($j = 1; $j<= $cont ; $j++)
                        {   
                            $dato= $this->db->fetch_object($resp);

                                $data[]= $dato;

                        }


                    }

                }
        return $data;


    }


// bueno, trae un entero de la cantidad de comprobantes del dia
    function cantidad_comprobantes(){

        $sql_comp = "
        
        SELECT COUNT(*) AS comprobante
        FROM `llx_facture` AS f   
        INNER JOIN llx_societe AS s 
        ON f.fk_soc = s.rowid 
        WHERE  f.datef = '".$this->hoy ."' AND f.fk_user_author =".$this->vendedor;
        
        $restotal = $this->db->query($sql_comp);
        $num = $this->db->num_rows($restotal);
        // si devuelve producto  entro al proceso
        if ($num)
        {

            $obj = $this->db->fetch_object($restotal);
            if ($obj)
            {

                $comprobante_total=array('comprobantes'=> $obj->comprobante);
            }
        }

        return $comprobante_total;
        
    }






//     function restar_cantidades($array_vendidos, $array_sin_cargo)
//     {



// var_dump($array_vendidos);

// var_dump($array_sin_cargo);

//         $limit= count($array_vendidos);

//         for ($i =0 ; $i <= $limit ; $i++)
//         {

//             $array_vendidos[$i]['cantidad'] = $array_vendidos[$i]['cantidad'] - $array_sin_cargo[$i]['cantidad'];

//         }

//         return $array_vendidos;


//     }


}

?>