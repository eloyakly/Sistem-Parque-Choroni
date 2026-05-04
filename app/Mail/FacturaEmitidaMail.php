<?php

namespace App\Mail;

use App\Models\Factura;
use App\Models\GastoMes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaEmitidaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $factura;
    public $gastoMes;

    public function __construct(Factura $factura, GastoMes $gastoMes)
    {
        $this->factura = $factura;
        $this->gastoMes = $gastoMes;
    }

    public function envelope(): Envelope
    {
        $mes = \Carbon\Carbon::parse($this->factura->fecha_vencimiento)->translatedFormat('F Y');
        return new Envelope(
            subject: 'Condominio Conjunto Residencial Parque Choroní II — Recibo ' . ucfirst($mes),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recibo',
        );
    }
}
