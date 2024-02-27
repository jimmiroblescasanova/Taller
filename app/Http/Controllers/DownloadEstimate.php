<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadEstimate extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Estimate $estimate)
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
            ->addItems($items);

        return $invoice->stream();
    }
}
