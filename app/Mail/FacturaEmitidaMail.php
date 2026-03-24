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

class FacturaEmitidaMail extends Mailable
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
        return new Envelope(
            subject: 'Parque Choroní | Nueva Factura de Condominio: ' . $this->factura->descripcion,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factura',
        );
    }
}
