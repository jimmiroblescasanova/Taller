<x-mail::message>
## {{ $order->contact->full_name }}

Le hacemos entrega de su orden de servicio del día {{ $order->created_at->format('d-m-Y') }} con folio OS-{{ $order->id }}:

> {{ $order->title }} para el vehiculo con placas {{ $order->vehicle->license_plate }}

Esta en formato PDF para una mejor visulización de la misma.

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
