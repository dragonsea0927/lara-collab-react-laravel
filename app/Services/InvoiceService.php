<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\OwnerCompany;
use App\Models\Task;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice as InvoiceGenerator;

class InvoiceService
{
    public static function generate(Invoice $invoice): void
    {
        $ownerCompany = OwnerCompany::with(['country'])->first();
        $clientCompany = $invoice->clientCompany;

        $invoice->load([
            'tasks' => fn ($query) => $query->withSum('timeLogs AS total_minutes', 'minutes'),
        ]);

        $owner = new Party([
            'name' => $ownerCompany->name,
            'phone' => $ownerCompany->phone,
            'address' => "$ownerCompany->address, $ownerCompany->postal_code $ownerCompany->city, {$ownerCompany->country->name}",
            'custom_fields' => [
                'business id' => $ownerCompany->business_id,
                'tax id' => $ownerCompany->tax_id,
                'vat' => $ownerCompany->tax > 0 ? $ownerCompany->vat : null,
            ],
        ]);

        $client = new Party([
            'name' => $clientCompany->name,
            'address' => "$clientCompany->address, $clientCompany->postal_code $clientCompany->city, {$clientCompany->country->name}",
            'custom_fields' => [
                'business id' => $clientCompany->business_id,
                'tax id' => $clientCompany->tax_id,
                'vat' => $clientCompany->vat,
            ],
        ]);

        $items = $invoice->tasks->map(function (Task $task) use ($invoice) {
            return InvoiceItem::make($task->name)
                ->pricePerUnit($invoice->hourly_rate / 100)
                ->quantity(round($task->total_minutes / 60, 2))
                ->units('hours');
        });

        $filename = today()->year.'/'.$invoice->number.' - '.trim($client->name);

        $pdf = InvoiceGenerator::make('invoice');

        if ($ownerCompany->tax > 0) {
            $pdf->taxRate($ownerCompany->tax / 100);
        }
        if (! empty($ownerCompany->logo)) {
            $pdf->logo(public_path($ownerCompany->logo));
        }
        if (! empty($invoice->note)) {
            $pdf->notes($invoice->note);
        }

        $pdf->sequence($invoice->number)
            ->serialNumberFormat('{SEQUENCE}')
            ->seller($owner)
            ->buyer($client)
            ->date($invoice->created_at)
            ->dateFormat('F j, Y')
            ->payUntilDays(14)
            ->currencySymbol($clientCompany->currency->symbol)
            ->currencyCode($clientCompany->currency->code)
            ->currencyDecimals($clientCompany->currency->decimals)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->setCustomData([
                'iban' => $ownerCompany->iban,
                'swift' => $ownerCompany->swift,
            ])
            ->filename($filename)
            ->addItems($items)
            ->save('invoices');

        $invoice->update(['filename' => $filename.'.pdf']);
    }
}
