<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Traits\SavesPdfToDisk;

class PdfEstimateController extends Controller
{
    use SavesPdfToDisk;

    public function __invoke(Estimate $estimate)
    {
        $pdf = $this->getPdfFile($estimate, 'cotizacion', 'COT');

        return $pdf->stream();
    }
}
