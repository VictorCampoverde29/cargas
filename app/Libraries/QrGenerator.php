<?php

namespace App\Libraries;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrGenerator
{
    public static function generarQR(string $texto, string $rutaSalida)
    {
        $options = new QROptions([
            'version'      => 20,
            'eccLevel'     => QRCode::ECC_L, // Compatible con tu versión 3.2.1
            'scale'        => 5,
            'imageBase64'  => false,
        ]);

        $qrcode = new QRCode($options);
        $imageData = $qrcode->render($texto);
        file_put_contents($rutaSalida, $imageData);
    }
}
