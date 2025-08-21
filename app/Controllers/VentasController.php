<?php

namespace App\Controllers;

use App\Models\VentasModel;
use CodeIgniter\Controller;

class VentasController extends Controller
{
    public function verificarVenta()
    {
        $ventasModel = new VentasModel();
        $numeroGuia = $this->request->getGet('numero_guia');
        $data = $ventasModel->verificarVentaPorGuia($numeroGuia);
        return $this->response->setJSON($data);
    }

    public function generarPDF($idguia)
    {
        require_once(APPPATH . 'Libraries/fpdf/fpdf.php');
        // Datos de sesión
        $rucempresa    = '20606466006';
        $nombreempresa = 'TRANSPORTES Y NEGOCIOS FOX S.A.C.';
        $dirempresa    = 'PRINCIPAL:CAL.JUAN TOMIS STACK NRO. 390 URB. SANTA MARIA LAMBAYEQUE - CHICLAYO - JOSE LEONARDO ORTIZ';
        $otros         = 'TELEFONOS:074-625845   WWW.GRUPOASIU.COM';
        $codigomtc     = '1596841CNG';
        // Conexión DB
        $db = \Config\Database::connect();
        $guia = $db->query("SELECT * FROM guia_transportista WHERE idguia_transportista = ?", [$idguia])->getRow();

        if (!$guia) {
            return "Guía no encontrada";
        }

        // Variables principales
        $numero         = $guia->numero;
        $femision       = $guia->fecha_emision;
        $fini_traslado  = $guia->fecha_iniciot;
        $direccionpartida = $guia->dir_partida;
        $ubigeopartida  = $guia->ubi_partida;
        $dirllegada     = $guia->dir_llegada;
        $ubillegada     = $guia->ubi_llegada;
        $indtrasbprog = $guia->ind_transbordoprog;
        $indtraspsubcontratado = $guia->ind_transpsubcontratado;
        $indipagaflete = $guia->ind_pagaflete;
        $codhash        = $guia->hash??'system';
        $ticket         = $guia->tk_sunat;

        // Crear instancia PDF
        $pdf = new \App\Libraries\PDFGuia($rucempresa, $nombreempresa, $dirempresa, $otros, $numero);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, utf8_decode('Información de Guía:'), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(33, 4, utf8_decode('N° MTC'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode(': ' . $codigomtc), 0, 1, 'L');
        $pdf->Cell(33, 4, utf8_decode('Fecha emisión'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode(': ' . $femision), 0, 1, 'L');
        $pdf->Cell(33, 4, utf8_decode('Fecha traslado'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode(': ' . $fini_traslado), 0, 1, 'L');
        $pdf->Cell(33, 4, utf8_decode('Punto partida'), 0, 0, 'L');
        $pdf->MultiCell(0, 4, utf8_decode(': ' . $direccionpartida . ' (' . $ubigeopartida . ')'),  0, 'L', false);
        $pdf->Cell(33, 4, utf8_decode('Punto llegada'), 0, 0, 'L');
        $pdf->MultiCell(0, 4, utf8_decode(': ' . $dirllegada . ' (' . $ubillegada . ')'),  0, 'L', false);
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');

        // Remitente
        $rem = $db->query("SELECT * FROM remitente WHERE idremitente = ?", [$guia->idremitente])->getRow();
        if ($rem) {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(0, 4, utf8_decode('Datos del Remitente:'), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(33, 4, 'RUC', 0, 0);
            $pdf->Cell(0, 4, ': ' . $rem->num_doc, 0, 1);
            $pdf->Cell(33, 4, utf8_decode('Razón Social'), 0, 0);
            $pdf->MultiCell(0, 4, ': ' . $rem->razon_social, 0);
        }
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
        // Destinatario
        $dest = $db->query("SELECT * FROM destinatario_guia WHERE iddestinatario_guia = ?", [$guia->iddestinatario_guia])->getRow();
        if ($dest) {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(0, 4, 'Datos del Destinatario:', 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(33, 4, 'RUC', 0, 0);
            $pdf->Cell(0, 4, ': ' . $dest->documento, 0, 1);
            $pdf->Cell(33, 4, utf8_decode('Razón Social'), 0, 0);
            $pdf->MultiCell(0, 4, ': ' . $dest->nombre, 0);
        }
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');

        // Documentos Relacionados
        $docs = $db->query("SELECT * FROM doc_rel_guia_transportista WHERE idguia_transportista = ?", [$idguia])->getResult();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, 'Documentos Relacionados:', 0, 1);
        $pdf->SetFont('Arial', '', 8);
        foreach ($docs as $d) {
            $pdf->MultiCell(0, 4, utf8_decode($d->tipo_desc . ' Nº ' . $d->numero) . ' - RUC ' . $d->ruc_emisor, 0);
        }
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
        // Bienes Transportados
        $bienes = $db->query("SELECT * FROM det_guia_transportista WHERE idguia_transportista = ?", [$idguia])->getResult();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, 'Bienes por transportar:', 0, 1);
        $pdf->Cell(10, 4, utf8_decode('Nº'), 1);
        $pdf->Cell(50, 4, utf8_decode('ITEM'), 1, 0, 'C');
        $pdf->Cell(10, 4, 'UM', 1);
        $pdf->Cell(10, 4, 'CANT', 1, 1);
        $pdf->SetFont('Arial', '', 8);
        $i = 1;
        foreach ($bienes as $b) {
         
            $pdf->Cell(10, 4, $i++, 0, 0, 'C');
            $pdf->SetFont('Arial', '',7);
            $pdf->MultiCell(70, 4, utf8_decode($b->descripcion), 0, 'L',false);
            $pdf->SetFont('Arial', '',8);
            $pdf->Cell(60, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode($b->umed), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode($b->cant), 0, 1, 'C');
          
        }
        $pdf->Cell(40, 4, utf8_decode('Unidad de Medida del Peso Bruto : KGM'), 0, 1, 'L');
        $pdf->Cell(0, 4, 'Peso Bruto Total: ' . $guia->peso . ' KGM', 0, 1);
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, utf8_decode('Datos del Traslado:'), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, utf8_decode('Indicador de Transbordo Programado : ' . $indtrasbprog), 0, 1, 'L');
        $pdf->Cell(0, 4, utf8_decode('Indicador de Transporte subcontratado : ' . $indtraspsubcontratado), 0, 1, 'L');
        $pdf->Cell(0, 4, utf8_decode('Indicador del pagador del flete : ' . $indipagaflete), 0, 1, 'L');
        $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
        if ($indtraspsubcontratado == 'SI') {
            $tsubc = $db->query("SELECT * FROM subcontratado WHERE idsubcontratado = ?", [$guia->idsubcontratado])->getRow();
            if ($tsubc) {
                $rucsubcnt = $tsubc->num_doc;
                $nombresubcnt = $tsubc->razon_social;
            }
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(0, 4, utf8_decode('Transp. Subcontratado:'), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(33, 4, utf8_decode('Ruc'), 0, 0, 'L');
            $pdf->Cell(0, 4, utf8_decode(': ' . $rucsubcnt), 0, 1, 'L');
            $pdf->Cell(33, 4, utf8_decode('Razón Social'), 0, 0, 'L');
            $pdf->MultiCell(0, 4, utf8_decode(': ' . $nombresubcnt),  0, 'L', false);
            $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
        }

        // Vehículos       
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, utf8_decode('Datos de los vehículos:'), 0, 1, 'L');

        // Vehículos Principales
        $vprimarios = $db->query("
            SELECT uni.placa, uni.cert_inscrip 
            FROM vehiculos_guia_transportista vgt 
            INNER JOIN unidades uni ON vgt.idunidades = uni.idunidades 
            WHERE vgt.tipo_v = 'Principal' AND vgt.idguia_transportista = ?
        ", [$idguia])->getResult();

                if (!empty($vprimarios)) {
                    $pdf->Cell(0, 4, utf8_decode('Principal'), 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);
                    foreach ($vprimarios as $valor) {
                        $pdf->Cell(0, 4, utf8_decode('Número de placa : ' . $valor->placa), 0, 1, 'L');
                        $pdf->Cell(0, 4, utf8_decode('N° TUCE / Cert. Habilitación Vehicular : ' . $valor->cert_inscrip), 0, 1, 'L');
                    }
                }

                // Vehículos Secundarios
                $vsecundarios = $db->query("
            SELECT uni.placa, uni.cert_inscrip 
            FROM vehiculos_guia_transportista vgt 
            INNER JOIN unidades uni ON vgt.idunidades = uni.idunidades 
            WHERE vgt.tipo_v = 'Secundario' AND vgt.idguia_transportista = ?
        ", [$idguia])->getResult();

                if (!empty($vsecundarios)) {
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 4, utf8_decode('Secundario'), 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);
                    foreach ($vsecundarios as $valor) {
                        $pdf->Cell(0, 4, utf8_decode('Número de placa : ' . $valor->placa), 0, 1, 'L');
                        $pdf->Cell(0, 4, utf8_decode('N° TUCE / Cert. Habilitación Vehicular : ' . $valor->cert_inscrip), 0, 1, 'L');
                    }
                }

                $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(0, 4, utf8_decode('Datos de los Conductores:'), 0, 1, 'L');
                $conductores = $db->query("SELECT  cgt.tipoc,cnd.nombres,cnd.apellidos,cnd.nro_doc,cnd.nro_licencia FROM conductor_guia_transportista cgt INNER JOIN conductor cnd ON cgt.idconductor=cnd.idconductor
            WHERE cgt.idguia_transportista = ?", [$idguia])->getResult();
                foreach ($conductores as $c) {
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 4, $c->tipoc, 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(40, 4, utf8_decode('Nombres : ' . $c->nombres), 0, 1, 'L');
                    $pdf->Cell(40, 4, utf8_decode('Apellidos : ' . $c->apellidos), 0, 1, 'L');
                    $pdf->Cell(40, 4, utf8_decode('Dni : ' . $c->nro_doc), 0, 1, 'L');
                    $pdf->Cell(40, 4, utf8_decode('Número licencia de conducir : ' . $c->nro_licencia), 0, 1, 'L');
                }
                $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(0, 4, utf8_decode('Datos de Pagador del Flete:'), 0, 1, 'L');
                $pdf->SetFont('Arial', '', 8);

                if ($indipagaflete == "TERCERO") {
                    $pflete = $db->query("SELECT pf.num_doc,pf.razon_social FROM paga_flete_guia_transportista pfgt INNER JOIN paga_flete pf ON pfgt.idpaga_flete=pf.idpaga_flete
                WHERE pfgt.idguia_transportista = ?", [$idguia])->getRow();
                    $pdf->Cell(33, 4, utf8_decode('Ruc'), 0, 0, 'L');
                    $pdf->Cell(0, 4, utf8_decode(': ' . $pflete->num_doc), 0, 1, 'L');
                    $pdf->Cell(33, 4, utf8_decode('Razón Social'), 0, 0, 'L');
                    $pdf->MultiCell(0, 4, utf8_decode(': ' . $pflete->razon_social),  0, 'L', false);
                } else {
                    $rem = $db->query("SELECT * FROM remitente WHERE idremitente = ?", [$guia->idremitente])->getRow();
                    $pdf->Cell(33, 4, utf8_decode('Ruc'), 0, 0, 'L');
                    $pdf->Cell(0, 4, utf8_decode(': ' . $rem->num_doc), 0, 1, 'L');
                    $pdf->Cell(33, 4, utf8_decode('Razón Social'), 0, 0, 'L');
                    $pdf->MultiCell(0, 4, utf8_decode(': ' . $rem->razon_social),  0, 'L', false);
                }

                $pdf->Cell(0, 2,  '.....................................................................................................', 0, 1, 'C');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(0, 4, utf8_decode('Observación:'), 0, 1, 'L');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(70, 4, utf8_decode($guia->glosa), 0, 'L', false);
                // Insertar código QR
                $currenty = $pdf->GetY();
                $qrData = $codhash;
                $size = 50;
                $tempDir = FCPATH . 'public/files/' . $rucempresa . '/qr/';
                $qrImagePath = $tempDir . "qrcode_" . $numero . ".png";

                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }

                // Insertar código QR
                $currenty = $pdf->GetY();
                $qrData = $codhash;
                $size = 50;
                $tempDir = FCPATH . 'public/files/' . $rucempresa . '/qr/';
                $qrImagePath = $tempDir . "qrcode_" . $numero . ".png";

                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }

                // Generar QR
                try {
                    \App\Libraries\QrGenerator::generarQR($qrData, $qrImagePath);
                    
                    if (file_exists($qrImagePath) && filesize($qrImagePath) > 100) {
                        $pdf->Image($qrImagePath, 20, $currenty, $size, $size, 'PNG');
                    } else {
                        $pdf->Cell(0, 4, utf8_decode('QR: ' . $codhash), 0, 1, 'L');
                    }
                } catch (\Exception $e) {
                    $pdf->Cell(0, 4, utf8_decode('QR: ' . $codhash), 0, 1, 'L');
                }
                
                $pdf->SetY($currenty + $size);
                $pdf->Cell(0, 4, utf8_decode('Ticket : ' . $ticket), 0, 1, 'C');

                if (file_exists($qrImagePath)) {
                    unlink($qrImagePath);
                }

                // Generar PDF directamente en memoria y enviarlo al navegador
                return $this->response
                    ->setHeader('Content-Type', 'application/pdf')
                    ->setHeader('Content-Disposition', 'inline; filename="' . $numero . '.pdf"')
                    ->setBody($pdf->Output('', 'S'));

    }
}
