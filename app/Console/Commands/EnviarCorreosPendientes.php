<?php

namespace App\Console\Commands;

use App\Models\LogCorreo;
use App\Models\Factura;
use App\Models\GastoMes;
use App\Models\Pago;
use App\Mail\FacturaEmitidaMail;
use App\Mail\ReciboPagoMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarCorreosPendientes extends Command
{
    protected $signature   = 'correos:enviar-pendientes';
    protected $description = 'Envía los correos pendientes del día anterior respetando el límite diario de ' . LogCorreo::LIMITE_DIARIO;

    public function handle(): int
    {
        $cupoDisponible = LogCorreo::LIMITE_DIARIO - LogCorreo::enviadosHoy();

        if ($cupoDisponible <= 0) {
            $this->warn('Límite diario ya alcanzado. No se enviará ningún correo pendiente hoy.');
            return self::SUCCESS;
        }

        $pendientes = LogCorreo::where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->limit($cupoDisponible)
            ->get();

        if ($pendientes->isEmpty()) {
            $this->info('No hay correos pendientes.');
            return self::SUCCESS;
        }

        $this->info("Cupo disponible: {$cupoDisponible}. Procesando {$pendientes->count()} correo(s) pendiente(s)...");

        $enviados = 0;
        $fallidos = 0;

        foreach ($pendientes as $log) {
            try {
                if ($log->tipo === 'factura') {
                    $factura = Factura::with('apartamento.propietario', 'apartamento.tipo')->find($log->datos_extra['factura_id'] ?? null);
                    $gastoMes = GastoMes::with('detalles')->find($log->datos_extra['gasto_mes_id'] ?? null);

                    if (!$factura || !$gastoMes || !$factura->apartamento?->propietario?->email) {
                        $log->update(['estado' => 'fallido', 'error' => 'Factura, gasto mes o propietario no encontrado al reintentar.']);
                        $fallidos++;
                        continue;
                    }

                    Mail::to($factura->apartamento->propietario->email)->send(new FacturaEmitidaMail($factura, $gastoMes));

                } elseif ($log->tipo === 'recibo_pago') {
                    $pago = Pago::with('apartamento.propietario', 'apartamento.tipo')->find($log->datos_extra['pago_id'] ?? null);

                    if (!$pago || !$pago->apartamento?->propietario?->email) {
                        $log->update(['estado' => 'fallido', 'error' => 'Pago o propietario no encontrado al reintentar.']);
                        $fallidos++;
                        continue;
                    }

                    // Regenerar PDF para adjuntar
                    $pdf = Pdf::loadView('pagos.recibo_pdf', compact('pago'));
                    $pdfContent = $pdf->output();

                    Mail::to($pago->apartamento->propietario->email)->send(new ReciboPagoMail($pago, $pdfContent));
                }

                // Marcar como enviado
                $log->update(['estado' => 'enviado', 'error' => null, 'datos_extra' => null]);
                $enviados++;
                $this->line("  ✅ Enviado a {$log->destinatario}");

            } catch (\Exception $e) {
                $log->update(['estado' => 'fallido', 'error' => 'Reintento fallido: ' . $e->getMessage()]);
                $fallidos++;
                $this->error("  ❌ Fallo al enviar a {$log->destinatario}: " . $e->getMessage());
            }
        }

        $this->info("Proceso finalizado. Enviados: {$enviados} | Fallidos: {$fallidos}");
        return self::SUCCESS;
    }
}
