<?php

namespace App\Traits;

use App\Models\Estimate;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

trait SavesPdfToDisk 
{
    public function getPdfUrl(Estimate $estimate)
    {
        return $this->generatePDF($estimate)->url();
    }

    public function getPdfFile(Estimate $estimate)
    {
        return $this->generatePDF($estimate);
    }

    protected function generatePDF(Estimate $estimate)
    {
        $seller = new Party([
            'name' => $estimate->agent->name,
        ]);

        $contact = new Party([
            'name'          => $estimate->contact->full_name,
            'phone'         => $estimate->contact->phone,
            'custom_fields' => [
                'email' => $estimate->contact->email,
            ],
        ]);

        $items = [];
        foreach ($estimate->items as $i => $item) {
            $items[$i] = InvoiceItem::make($item->product->name)
                ->quantity($item->quantity)
                ->pricePerUnit($item->price);
        }

        $invoice = Invoice::make()
            ->template('estimate')
            ->name('cotización')
            ->series('COT')
            ->sequence($estimate->id)
            ->seller($seller)
            ->buyer($contact)
            ->payUntilDays(15)
            ->taxRate(16)
            ->addItems($items)
            ->save('public');

        return $invoice;
    }

}