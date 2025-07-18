<?php

namespace App\Libraries;

require_once(APPPATH . 'Libraries/fpdf/fpdf.php');

class PDFGuia extends \FPDF
{
    protected $rucempresa;
    protected $nombreempresa;
    protected $dirempresa;
    protected $otros;
    protected $numero;

    public function __construct($rucempresa, $nombreempresa, $dirempresa, $otros, $numero)
    {
        parent::__construct('P', 'mm', array(90, 800));
        $this->rucempresa = $rucempresa;
        $this->nombreempresa = $nombreempresa;
        $this->dirempresa = $dirempresa;
        $this->otros = $otros;
        $this->numero = $numero;
    }

    function Header()
    {
        $this->SetMargins(5, 2);
        // Centrando imagen horizontalmente (ancho = 50)
        $logoWidth = 60;
        $pageWidth = $this->GetPageWidth();
        $centerX = ($pageWidth - $logoWidth) / 2;

        $this->Image(APPPATH . '../public/dist/img/logofoxnegro.png', $centerX, 2, $logoWidth);

        $this->SetY(20);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 4, $this->rucempresa, 0, 1, 'C');
        $this->MultiCell(0, 4, $this->nombreempresa, 0, 'C', false);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(0, 4, utf8_decode('Dirección:' . $this->dirempresa), 0, 'C', false);
        $this->Cell(0, 4, utf8_decode($this->otros), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 4, utf8_decode('GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 30);
        $this->Cell(0, 8, $this->numero, 0, 1, 'C');
        $this->Cell(0, 2, '..................................................................................', 0, 1, 'C');
        $this->Ln(2);
    }
}
