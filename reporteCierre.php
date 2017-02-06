<?php
 $res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

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
            $this->SetFont('Arial','B',12);
            $this->Cell(0,15,"Cierre Diario ".$_SESSION["firstname"],0,1,'C');
            // Movernos a la derecha
            $this->Cell(80);
            // Título
            $this->Cell(30,10, date("d-m-Y"),1,0,'C');
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
        }



    
        function cargar_total ($val_total)
        {

            $this->SetFont('Arial','',20);
            $this->Cell(0,20,'Total $ '.$val_total ,0,0,'L');

        }

}
















if (isset($_SESSION["stock_print"]) && isset($_SESSION["stock_total"])){



        $pdf = new PDF();
        // Títulos de las columnas

        $header = array('ref', 'Nombre', 'cantidad');
        // Carga de datos
        $dato_stock = $_SESSION["stock_print"];
        $dato_total = $_SESSION["stock_total"];

        $pdf->SetFont('Arial','',11);
        $pdf->AddPage();
        $pdf->SetTextColor(0);

        $pdf->carga_tabla($header,$dato_stock);
        $pdf->cargar_total( $dato_total['total']);



        $file_name = str_replace ( " " , "_" , $_SESSION["lastname"] ). date("d-m-Y").".pdf";
        // guardar en server
        $pdf->Output( "tmp_repo/".$file_name , "F");

        // descargar Cliente
        $pdf->Output( $file_name , "D");
        //liberar variables

        unset($_SESSION["stock_print"]) ;
        unset($_SESSION["stock_total"]);



}else{

    echo "No hay datos disponibles para imprimir";
}


?>