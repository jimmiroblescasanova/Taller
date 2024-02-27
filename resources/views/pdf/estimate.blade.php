<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización: {{ $estimate->id }}</title>

    <style type="text/css" media="all">
        body {
            background: white;
            font-family: "Nunito", sans-serif;
            font-size: 9px;
        }
        table {
            /*border: 1px solid #999;*/
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }
        th, td {
            padding: 0;
            word-wrap: break-word;
        }

        .row {
            display: block;
            margin-bottom: .5em;
        }
        .column {
            display: inline-block;
            vertical-align: top;
        }
        .table {
            border: 0.5px solid #999;
        }
        .table tr:first-child {
            background-color: #f2f2f2;
        }
        .table td {
            font-size: 8px;
            padding: 3px;
        }
        /* Estilos para los encabezados */
        #encabezado {
            margin-bottom: 25px;
        }
        #encabezado .logo {
            text-align: left;
            vertical-align: top;
            width: 100px;
        }
        #encabezado .fecha {
            text-align: right;
            vertical-align: top;
        }
        #encabezado .emisor {
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }
        /* Conceptos */
        #conceptos table td{
            border: 0.5px solid #999;
            font-size: 8px;
        }
        #conceptos table tr:first-child {
            text-align: center;
            vertical-align: middle;
        }
        #conceptos table tr:nth-child(odd) {
            background-color:#f2f2f2;
        }
        #conceptos .impuestos {
            font-size: 7px;
        }
        /* Zona de leyendas y subtotales */
        #impuestos table td {
            text-align: right;
        }
        #impuestos .leyendas {
            text-align: left;
        }

        #cadenas .cadenasDigitales {
            font-size: 6px;
        }
        #cadenas .qr {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
{{-- Encabezados --}}
<div class="row" id="encabezado">
    <table>
        <tr>
            <td class="logo">
                <img src="{{ public_path('logo.jpg') }}" alt="logotipo" width="80px">
            </td>
            <td class="fecha">
                {{ $estimate->created_at }}
            </td>
        </tr>
        <tr class="emisor">
            <td colspan="2">
                <b></b>
            </td>
        </tr>
        <tr class="emisor">
            <td colspan="2">
                
            </td>
        </tr>
    </table>
</div>
{{-- Datos fiscales del documento --}}
<div class="row" id="datosDocumento">
    <div class="column" style="width: 40%">
        <table class="table">
            <tr>
                <td>
                    <b>Folio Fiscal:</b> 
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
            </tr>
        </table>
    </div>
    <div class="column" style="width: 15%">&nbsp;</div>
    <div class="column" style="width: 40%">
        <table class="table">
            <tr>
                <td>
                    <b>Serie y Folio: </b> 
                </td>
            </tr>
            <tr>
                <td>
                    {{ $estimate->id }}
                </td>
            </tr>
        </table>
    </div>
</div>
{{-- Receptor --}}
<div class="row" id="receptor">
    <table class="table">
        <tr>
            <td><strong>COTIZADO A:</strong> </td>
        </tr>
        <tr>
            <td>
                Contacto: {{ $estimate->contact->full_name }} <br />
                Email: {{ $estimate->contact->email }} <br />
                Teléfono: {{ $estimate->contact->phone }}
            </td>
        </tr>
    </table>
</div>
{{-- Movimientos del documento --}}
<div class="row" id="conceptos">
    <table>
        <tr>
            <td>Cantidad</td>
            <td>Unidad</td>
            <td>Clave Prod/Serv</td>
            <td style="width: 50%;">Descripción</td>
            <td>Precio Unitario</td>
            <td>Importe</td>
        </tr>
        
    </table>
</div>
{{-- Zona de leyendas y subtotales --}}
<div class="row" id="impuestos">
    <table>
        <tr>
            <td rowspan="4" colspan="4" class="leyendas">
                Este documento es una representación impresa de un CFDI. <br>
{{--                CANTIDAD CON LETRA--}}
            </td>
            <td>Subtotal:</td>
            <td>$ </td>
        </tr>
        <tr>
            <td>Impuestos Trasladados:</td>
            <td>$ </td>
        </tr>
        <tr>
            <td>Impuestos Retenidos:</td>
            <td>
                
            </td>
        </tr>
        <tr>
            <td>TOTAL:</td>
            <td>$ </td>
        </tr>
    </table>
</div>
{{-- Cadenas digitales --}}
<div class="row" id="cadenas">
    <table>
        <tr>
            <td rowspan="2" class="qr">
                
            </td>
            <td style="width: 75%;">Sello digital emisor: <br>
                <span class="cadenasDigitales">
                    
                </span>
            </td>
        </tr>
        <tr>
            <td>Sello digital SAT: <br>
                <span class="cadenasDigitales">
                   
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">Cadena Original <br>
                <span class="cadenasDigitales">
                    
                </span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>