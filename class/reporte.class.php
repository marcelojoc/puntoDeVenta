<?php

class Reporte {

	var $db;

	var $vendedor;
	var $hoy;
    var $caja;
	// var $reponse;

	// var $sqlQuery;

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
        if ($num){

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




    function get_cantidad_unidades(){}



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

                        // print'<tr>';
                        // print '<td>'. $obj->datef.'</td>';
                        // print '<td>'. $dato->description .'</td>';
                        // print '<td>'. $dato->qty .'</td>';
                        // print'</tr>';

                }


            }

            return $productos;

        }


    }













}

?>