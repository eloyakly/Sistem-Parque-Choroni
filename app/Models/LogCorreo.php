<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogCorreo extends Model
{
    protected $table = 'log_correos';

    protected $fillable = [
        'tipo',
        'destinatario',
        'asunto',
        'referencia_id',
        'estado',
        'error',
        'datos_extra',
    ];

    protected $casts = [
        'datos_extra' => 'array',
    ];

    const LIMITE_DIARIO = 80;

    /**
     * Cuenta cuántos correos se han enviado exitosamente hoy.
     */
    public static function enviadosHoy(): int
    {
        return static::whereDate('updated_at', today())
            ->where('estado', 'enviado')
            ->count();
    }

    /**
     * Verifica si se puede enviar otro correo hoy.
     */
    public static function puedeEnviarHoy(): bool
    {
        return static::enviadosHoy() < static::LIMITE_DIARIO;
    }

    /**
     * Registra un correo enviado exitosamente.
     */
    public static function registrarEnvio(string $tipo, string $destinatario, string $asunto, ?int $referenciaId = null): self
    {
        return static::create([
            'tipo'          => $tipo,
            'destinatario'  => $destinatario,
            'asunto'        => $asunto,
            'referencia_id' => $referenciaId,
            'estado'        => 'enviado',
        ]);
    }

    /**
     * Registra un correo fallido.
     */
    public static function registrarFallo(string $tipo, string $destinatario, string $asunto, string $error, ?int $referenciaId = null): self
    {
        return static::create([
            'tipo'          => $tipo,
            'destinatario'  => $destinatario,
            'asunto'        => $asunto,
            'referencia_id' => $referenciaId,
            'estado'        => 'fallido',
            'error'         => $error,
        ]);
    }

    /**
     * Registra un correo pendiente (se enviará cuando haya cupo al día siguiente).
     * datos_extra guarda la información necesaria para reconstruir el correo.
     */
    public static function registrarPendiente(string $tipo, string $destinatario, string $asunto, array $datosExtra, ?int $referenciaId = null): self
    {
        return static::create([
            'tipo'          => $tipo,
            'destinatario'  => $destinatario,
            'asunto'        => $asunto,
            'referencia_id' => $referenciaId,
            'estado'        => 'pendiente',
            'error'         => 'Límite diario de ' . static::LIMITE_DIARIO . ' correos alcanzado. Se enviará al día siguiente.',
            'datos_extra'   => $datosExtra,
        ]);
    }

    /**
     * Cantidad de correos pendientes por enviar.
     */
    public static function totalPendientes(): int
    {
        return static::where('estado', 'pendiente')->count();
    }
}
