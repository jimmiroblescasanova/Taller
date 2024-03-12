<?php

namespace App\Traits;

use LaravelDaily\Invoices\Invoice;
use Illuminate\Database\Eloquent\Model;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

trait SavesPdfToDisk 
{
    public function getPdfUrl(Model $model, string $name = null, string $series = null): string
    {
        return $this->generatePDF($model, $name, $series)->url();
    }

    public function getPdfFile(Model $model, string $name = null, string $series = null): Invoice
    {
        return $this->generatePDF($model, $name, $series);
    }

    protected function generatePDF($model, $name, $series)
    {
        $seller = new Party([
            'name' => $model->agent->name,
        ]);

        $contact = new Party([
            'name'          => $model->contact->full_name,
            'phone'         => $model->contact->phone,
            'custom_fields' => [
                'email' => $model->contact->email,
            ],
        ]);

        $items = [];
        foreach ($model->items as $i => $item) {
            $items[$i] = InvoiceItem::make($item->product->name)
                ->quantity($item->quantity)
                ->pricePerUnit($item->price);
        }

        // TODO: cambiar la plantilla o ver como hacerla dinamica ¿?
        $invoice = Invoice::make()
            ->template('estimate')
            ->name($name ?? config('app.name'))
            ->series($series ?? '')
            ->sequence($model->id)
            ->seller($seller)
            ->buyer($contact)
            ->payUntilDays(15)
            ->taxRate(16)
            ->addItems($items)
            ->save('public');

        return $invoice;
    }

}