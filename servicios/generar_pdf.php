<?php
require_once('/var/www/html/PROYEC-EVALUACION/assets/vendor/libs/fpdf/fpdf.php');

/*if (isset($_POST['datos_filtrados'])) {
    $datos = json_decode($_POST['datos_filtrados'], true);
    $fechaActual = date('d/m/Y');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Reporte de Evaluadores',0,1,'C');
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(0,10,'Fecha: '.$fechaActual,0,1,'C');
    $pdf->Ln(10);

    // Encabezados
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(25,10,'Cédula',1);
    $pdf->Cell(50,10,'Nombre',1);
    $pdf->Cell(40,10,'Cargo',1);
    $pdf->Cell(30,10,'Periodo',1);
    $pdf->Cell(20,10,'Año',1);
    $pdf->Cell(25,10,'Puntaje',1);
    $pdf->Cell(30,10,'Rango',1);
    $pdf->Ln();

    // Datos filtrados
    $pdf->SetFont('Arial','',10);
    foreach($datos as $fila) {
        $pdf->Cell(25,10,$fila['cedula_usuario'],1);
        $pdf->Cell(50,10,$fila['nombre_completo'],1);
        $pdf->Cell(40,10,$fila['cargo_evaluado'],1);
        $pdf->Cell(30,10,$fila['periodo_evaluado'],1);
        $pdf->Cell(20,10,$fila['anio_inicio'],1);
        $pdf->Cell(25,10,$fila['puntaje_final'],1);
        $pdf->Cell(30,10,$fila['rango_actuacion'],1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'reporte_evaluadores.pdf');
    exit;
} else {
    echo "No hay datos para exportar.";
    exit;
}Ç*/
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Reporte de Evaluadores FILTRADOS',0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página '.$this->PageNo().' - '.date('d/m/Y H:i'),0,0,'C');
    }

    function InfoFiltro($info) {
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(240,240,240);
        $this->Cell(0,8,'INFORMACIÓN DEL FILTRO',0,1,'C');
        
        $this->SetFont('Arial','',9);
        $this->Cell(0,6,'Total original: '.$info['total_original'].' | Filtrados: '.$info['total_filtrados'],0,1);
        if($info['texto_busqueda']) {
            $this->Cell(0,6,'Búsqueda: "'.$info['texto_busqueda'].'"',0,1);
        }
        if($info['filtro_cargo'] !== 'Ninguno') {
            $this->Cell(0,6,'Cargo: '.$info['filtro_cargo'],0,1);
        }
        $this->Ln(5);
    }

    function TablaDatos($datos) {
        // Encabezados
        $this->SetFont('Arial','B',9);
        $this->SetFillColor(200,220,255);
        $this->Cell(25,7,'Cédula',1,0,'C',true);
        $this->Cell(50,7,'Nombre',1,0,'C',true);
        $this->Cell(40,7,'Cargo',1,0,'C',true);
        $this->Cell(30,7,'Periodo',1,0,'C',true);
        $this->Cell(20,7,'Año',1,0,'C',true);
        $this->Cell(25,7,'Puntaje',1,0,'C',true);
        $this->Cell(30,7,'Rango',1,1,'C',true);

        // Datos FILTRADOS
        $this->SetFont('Arial','',8);
        foreach($datos as $fila) {
            $this->Cell(25,6,$fila['cedula_usuario'],1,0,'C');
            $this->Cell(50,6,utf8_decode($fila['nombre_completo']),1,0,'L');
            $this->Cell(40,6,utf8_decode($fila['cargo_evaluado']),1,0,'L');
            $this->Cell(30,6,$fila['periodo_evaluado'],1,0,'C');
            $this->Cell(20,6,$fila['anio_inicio'],1,0,'C');
            $this->Cell(25,6,$fila['puntaje_final'],1,0,'C');
            $this->Cell(30,6,$fila['rango_actuacion'],1,1,'C');
        }
    }
}

if(isset($_POST['datos_tabla'])) {
    $datos = json_decode(stripslashes($_POST['datos_tabla']), true);
    $infoFiltro = isset($_POST['info_filtro']) ? json_decode(stripslashes($_POST['info_filtro']), true) : [];
    
    $pdf = new PDF('L','mm','A4');
    $pdf->AddPage();
    
    if(!empty($infoFiltro)) {
        $pdf->InfoFiltro($infoFiltro);
    }
    
    $pdf->TablaDatos($datos);
    
    $nombre_archivo = 'reporte_evaluadores_' . date('Y-m-d_H-i-s') . '.pdf';
    $pdf->Output('D', $nombre_archivo);
    exit;
} else {
    echo "<div class='alert alert-danger'>No hay datos para exportar.</div>";
}
?>
