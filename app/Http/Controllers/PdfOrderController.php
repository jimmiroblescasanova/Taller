<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\SavesPdfToDisk;

class PdfOrderController extends Controller
{
    use SavesPdfToDisk;

    public function __invoke(Order $order)
    {
        $pdf = $this->getPdfFile($order, name: 'orden', series: 'OS');

        return $pdf->stream();
    }
}
