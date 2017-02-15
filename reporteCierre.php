<?php
 $res=0;

if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/reporte.class.php';
require('lib/fpdf.php');

class PDF extends FPDF
{
    // Cargar los datos

       

        function LoadData($file)
        {
            // Leer las líneas del fichero
            $lines = file($file);
            $data = array();
            foreach($lines as $line)
                $data[] = explode(';',trim($line));
            return $data;
        }



        function Header()
        {
            // Logo

            // Arial bold 15   date("Y-m-d H:i:s");
            $this->SetFont('Arial','B',16);
            $this->Cell(0,15,"Cierre Diario ".$_SESSION["firstname"],0,1,'C');
            // Movernos a la derecha
            $this->Cell(80);
            // Título
            $this->Cell(30,10, date("d-m-Y"),0,0,'C');
            // Salto de línea
            $this->Ln(20);
        }

        // Pie de página
        function Footer()
        {
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }


        // Tabla coloreada
        function carga_tabla($header, $data)
        {


            $this->SetFont('Arial','',18);
            $this->Cell(0,15,"Stock actual ",0,1,'L');
            $this->SetFont('Arial','',8);
            // Color de fondo
            $this->SetFillColor(200,220,255);
            // Título

            // Salto de línea
            $this->Ln(4);
            // Colores, ancho de línea y fuente en negrita
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // Cabecera
            $w = array(20, 80, 80, 10);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Restauración de colores y fuentes
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Datos
            
            foreach($data as $row)
            {
                $this->Cell($w[0],6,$row['ref'],'LRB',0,'C');
                $this->Cell($w[1],6,$row['nombre'],'LRB',0,'L');
                $this->Cell($w[2],6,$row['cantidad'],'LRB',0,'C');
                $this->Ln();
                
            }
            // Línea de cierre
            //$this->Cell(array_sum($w),0,'','T');

            $this->SetFont('Arial','',16);
            $this->Cell(0,20,'Comprobantes' ,0,0,'L');
            $this->Ln(6);
        }



    
        function cargar_total ($val_total)
        {

            $this->SetFont('Arial','',20);
            $this->Cell(0,20,'Total $ '.$val_total ,0,0,'L');

        }



        // estas funciones generan el bloque para cada venta
        function carga_detalle($detalle_c, $obj_detalle)
        {
            $cabecera=array('ref', 'Producto', 'cantidad');

            $this->Ln(4);
            $this->cliente_detalle($detalle_c);
            $this->tabla_detalle($detalle_c->rowid, $cabecera, $obj_detalle );
            $this->Ln(6);

        }


        // este metodo dibuja la tabla con los detalles del comprobante
        function tabla_detalle($id_comprobante, $header, $obj_detalle)
        {

            $this->Ln(6);
            // Colores, ancho de línea y fuente en negrita
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // Cabecera
            $w = array(20, 80, 80, 10);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Restauración de colores y fuentes
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // traigo el detalle de cada comprobante

            $repo = $obj_detalle->detalle_comprobante($id_comprobante);                 
            foreach($repo as $row)
            {
                $this->Cell($w[0],6,$row->rowid,'LRB',0,'C');
                $this->Cell($w[1],6,$row->description,'LRB',0,'L');
                $this->Cell($w[2],6,$row->qty,'LRB',0,'C');
                $this->Ln();
                
            }
 
        }



        // este metodo dibuja el detalle del cliente al que se le vendio
        function cliente_detalle($deltalle)
        {
            $this->Ln(6);
            $this->SetFont('Arial','',10);
            $this->Cell(0,20, 'Cliente: '.$deltalle->nom ,0,0,'L');
            $this->Ln(4);
            $this->Cell(0,20, 'Codigo: '.$deltalle->code_client ,0,0,'L');
            $this->Ln(4);
            $this->Cell(0,20, 'Monto: $ '.round($deltalle->total,2) ,0,0,'L');
            $this->Ln(6);
        }




        function carga_vendidas ($header, $obj_detalle)
        {
            $productos = $obj_detalle->get_cantidad_unidades(); 



            $this->Ln(12);
            // Colores, ancho de línea y fuente en negrita
            $this->SetFont('Arial','',16);
            $this->Cell(0,15,"Unidades vendidas",0,1,'L');
            $this->SetFont('Arial','',8);
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // Cabecera
            $w = array(20, 80, 80, 10);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Restauración de colores y fuentes
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // traigo el detalle de cada comprobante

                
            foreach($productos as $row)
            {
                $this->Cell($w[0],6,$row['ref'],'LRB',0,'C');
                $this->Cell($w[1],6,$row ['label'],'LRB',0,'L');
                $this->Cell($w[2],6,$row['cantidad'],'LRB',0,'C');
                $this->Ln();
                
            }

        }






        function sin_cargo ($header, $obj_detalle)
        {
            $productos = $obj_detalle->get_cantidad_unidades(100); 



            $this->Ln(12);
            // Colores, ancho de línea y fuente en negrita
            $this->SetFont('Arial','',16);
            $this->Cell(0,15,"Sin Cargo",0,1,'L');
            $this->SetFont('Arial','',8);
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetDrawColor(0,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // Cabecera
            $w = array(20, 80, 80, 10);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Restauración de colores y fuentes
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // traigo el detalle de cada comprobante

                
            foreach($productos as $row)
            {
                $this->Cell($w[0],6,$row['ref'],'LRB',0,'C');
                $this->Cell($w[1],6,$row ['label'],'LRB',0,'L');
                $this->Cell($w[2],6,$row['cantidad'],'LRB',0,'C');
                $this->Ln();
                
            }

        }



}














if (isset($_SESSION["stock_print"]) && isset($_SESSION["detalle_cliente"])){

//if (true){

        $hoy =date("Y-m-d");  // seteo la fecha de hoy
        $reporte=new Reporte($db, $_SESSION['uid'], $hoy, $_SESSION['CASHDESK_ID_BANKACCOUNT_CASH'] );  // instancia de reporte

        $pdf = new PDF();
        $header = array('ref', 'Nombre', 'cantidad');
        // Carga de datos
        $dato_stock = $_SESSION["stock_print"];
        $detalle_cliente = $_SESSION["detalle_cliente"];

        $pdf->SetFont('Arial','',11);
        $pdf->AddPage();
        $pdf->SetTextColor(0);

        $pdf->carga_tabla($header,$dato_stock);  // carga la tabla de Stock actual


        // el array de detalles de los comprobantes, hace un bucle para llamar  la funcion para dibujarlos
        foreach ($detalle_cliente as $d_cliente)
        {
            $pdf->carga_detalle($d_cliente, $reporte);
        }


        $pdf->carga_vendidas($header, $reporte);
        $pdf->sin_cargo($header, $reporte);
        
        // solo dibuja el total al final de la pagina
        $pdf->cargar_total( $reporte->get_monto_caja()['total']);


        $file_name = str_replace ( " " , "_" , strtolower ($_SESSION["uname"]) )."_". date("d_m_Y").".pdf";
        // guardar en server
        $pdf->Output( "tmp_repo/".$file_name , "F");

        //descargar Cliente
        $pdf->Output( $file_name , "D");
        //liberar variables

        unset($_SESSION["stock_print"]) ;
        unset($_SESSION["detalle_cliente"]);



}else{

    echo "No hay datos disponibles para imprimir o  el reporte ya fue descargado";
    echo"<br>";
    echo"<a  href= 'resumenVenta.php' >Volver al resumen</a>";
}


?>