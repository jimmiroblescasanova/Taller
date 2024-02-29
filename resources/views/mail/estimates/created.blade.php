<x-mail::message>
## {{ $estimate->contact->full_name }}

Le hacemos entrega de su cotizacion:

> C-{{ $estimate->id }}/{{ $estimate->title }}

en formato PDF para una mejor visulización de la misma.

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
