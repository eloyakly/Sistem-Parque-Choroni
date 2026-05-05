<?php

namespace App\Jobs;

use App\Models\LogCorreo;
use App\Models\Factura;
use App\Models\GastoMes;
use App\Models\Pago;
use App\Mail\FacturaEmitidaMail;
use App\Mail\ReciboPagoMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcesarEnvioCorreoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $logId;

    public function __construct($logId)
    {
        $this->logId = $logId;
    }

    public function handle(): void
    {
        $log = LogCorreo::find($this->logId);

        if (!$log || $log->estado !== 'pendiente') {
            return;
        }

        // Respetar el límite diario (si el límite se alcanza, el log se queda como 'pendiente' para el comando diario)
        if (!LogCorreo::puedeEnviarHoy()) {
            return;
        }

        try {
            if ($log->tipo === 'factura') {
                $factura = Factura::with('apartamento.propietario', 'apartamento.tipo')->find($log->datos_extra['factura_id'] ?? null);
                $gastoMes = GastoMes::with('detalles')->find($log->datos_extra['gasto_mes_id'] ?? null);

                if (!$factura || !$gastoMes || !$factura->apartamento?->propietario?->email) {
                    throw new \Exception('Factura, gasto mes o propietario no encontrado.');
                }

                Mail::to($factura->apartamento->propietario->email)->send(new FacturaEmitidaMail($factura, $gastoMes));

            } elseif ($log->tipo === 'recibo_pago') {
                $pago = Pago::with('apartamento.propietario', 'apartamento.tipo')->find($log->datos_extra['pago_id'] ?? null);

                if (!$pago || !$pago->apartamento?->propietario?->email) {
                    throw new \Exception('Pago o propietario no encontrado.');
                }

                // Generar PDF para adjuntar
                $pdf = Pdf::loadView('pagos.recibo_pdf', compact('pago'));
                $pdfContent = $pdf->output();

                Mail::to($pago->apartamento->propietario->email)->send(new ReciboPagoMail($pago, $pdfContent));
            }

            // Marcar como enviado si no hay excepción
            $log->update(['estado' => 'enviado', 'error' => null, 'datos_extra' => null]);

        } catch (\Exception $e) {
            // Guardar el error real proporcionado por el Exception
            $log->update(['estado' => 'fallido', 'error' => $e->getMessage()]);
        }
    }
}
