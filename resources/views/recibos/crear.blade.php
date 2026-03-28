@extends('layouts.plantilla')

@section('titulo', 'Emitir Recibo Mensual')

@section('contenido')
    <!-- Tom Select CDN para selectores de texto dinámicos -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        .ts-control {
            border-radius: 8px;
            border: 1px solid var(--color-borde);
            background: var(--color-superficie);
            color: var(--color-texto);
            padding: 0.8rem;
            font-family: inherit;
            font-size: 1rem;
        }
        .ts-dropdown {
            border-radius: 8px;
            background: var(--color-superficie);
            color: var(--color-texto);
            border: 1px solid var(--color-borde);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .ts-dropdown .active {
            background-color: var(--color-acentuar-suave);
            color: var(--color-texto);
        }
    </style>

    <!-- Modal Nativo Configurado -->
    <dialog id="modalEmail" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--color-borde); background: var(--color-superficie); box-shadow: 0 10px 30px rgba(0,0,0,0.15); max-width: 450px; text-align: center; margin: auto;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📧</div>
        <h3 style="margin-top: 0; color: var(--color-texto);">Confirmar Envío de Correos</h3>
        <p style="color: var(--color-texto-secundario); margin-bottom: 1.5rem; line-height: 1.5;">
            Al generar el/los <strong>recibo(s)</strong>, el sistema procesará la deuda y enviará automáticamente un desglose detallado por <strong>correo electrónico</strong> a los propietarios correspondientes. ¿Desea proceder?
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button type="button" class="boton" onclick="document.getElementById('modalEmail').close();" style="background: var(--color-borde); color: var(--color-texto);">Cancelar</button>
            <button type="button" class="boton boton-primario" onclick="enviarFormularioPendiente()">Confirmar y Enviar</button>
        </div>
    </dialog>

    <!-- Errores si aplican (una sola vez) -->
    @if($errors->any())
        <div style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
            <strong>Corrija los siguientes errores:</strong>
            <ul style="margin-top: 0.5rem; padding-left: 1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Formulario Masivo -->
    <div class="tarjeta" style="margin-bottom: 2rem;">
        <h1>Generación Masiva por Tipo de Inmueble</h1>
        <p style="color: var(--color-texto-secundario);">Emite recibos a todos los apartamentos de un tipo específico de una sola vez.</p>
        
        <form action="{{ route('recibos.masiva') }}" method="POST" style="margin-top: 2rem; background: var(--color-acentuar-suave); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-acentuar);">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; align-items: end;">
                <!-- Selección de Mes Cargado -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Mes de Gastos</label>
                    <select name="gasto_mes_id" id="select-mes-masivo" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">Seleccione mes...</option>
                        @foreach($gastos as $gasto)
                            <option value="{{ $gasto->id }}" {{ old('gasto_mes_id') == $gasto->id ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($gasto->mes_anio)->translatedFormat('F Y') }} ($ {{ number_format($gasto->total_gastos, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección de Tipo de Apartamento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Tipo de Inmueble</label>
                    <select name="tipo_apartamento_id" id="select-tipo-masivo" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="">Seleccione tipo...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" data-alicuota="{{ $tipo->alicuota }}">
                                {{ $tipo->nombre }} (Alícuota: {{ $tipo->alicuota }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Vencimiento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', now()->addDays(15)->format('Y-m-d')) }}" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>
            
            <!-- Proyección del Lote Masivo -->
            <div style="margin-top: 2rem; background: var(--color-acentuar-suave); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-acentuar);">
                <h3 style="color: var(--color-acentuar); margin-bottom: 0.5rem;">Impacto Masivo</h3>
                <p style="margin: 0 0 1rem 0; color: var(--color-texto-secundario);">
                    Inmuebles a Recibir Recibo: <strong id="conteo-aptos-masivo" style="color: var(--color-texto); font-size: 1.2rem;">0</strong>
                </p>
                
                <h4 style="margin-top: 1rem; margin-bottom: 0.5rem; color: var(--color-texto);">Costo Proyectado por Unidad</h4>
                <div style="background: #fff; border: 1px solid var(--color-borde); border-radius: 8px; overflow: hidden; margin-bottom: 1rem;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                        <thead>
                            <tr style="background: var(--color-superficie); border-bottom: 1px solid var(--color-borde); text-align: left;">
                                <th style="padding: 0.8rem;">Concepto del Mes</th>
                                <th style="padding: 0.8rem;">Costo Global ($)</th>
                                <th style="padding: 0.8rem;">Fracción Válida ($)</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-desglose-masivo">
                            <tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Seleccione mes y tipo para poblar proyección</td></tr>
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--color-acentuar); padding-top: 1rem;">
                    <span style="font-size: 1.1rem; font-weight: bold;">Cada inmueble recibirá un recibo de:</span>
                    <span id="monto-total-masivo" style="font-size: 1.6rem; font-weight: bold; color: var(--color-acentuar);">$ 0.00</span>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="button" class="boton boton-primario" onclick="abrirModal(this.closest('form'))">Ejecutar Recibos Masivos</button>
            </div>
        </form>
    </div>

    <!-- Formulario Individual (Existente) -->
    <div class="tarjeta">
        <h1>Generar Recibo Individual</h1>
        <p style="color: var(--color-texto-secundario);">Seleccione el mes y un único apartamento para emitir un recibo individualmente.</p>
        
        <form action="{{ route('recibos.store') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Selección de Mes Cargado -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Seleccionar Mes de Gastos</label>
                    <select name="gasto_mes_id" id="select-mes" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                        <option value="" data-total="0">Seleccione un mes...</option>
                        @foreach($gastos as $gasto)
                            <option value="{{ $gasto->id }}" data-total="{{ $gasto->total_gastos }}" {{ old('gasto_mes_id') == $gasto->id ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($gasto->mes_anio)->translatedFormat('F Y') }} (Base: $ {{ number_format($gasto->total_gastos, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección de Propietario -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;" class="ts-wrapper-container">
                    <label style="font-weight: 500;">Filtrar Propietario (Cédula o Nombre)</label>
                    <select id="select-propietario" placeholder="Buscar propietario...">
                        <option value="">Todos los inmuebles...</option>
                        @php
                            $propietariosUnicos = $apartamentos->pluck('propietario')->unique('id')->sortBy('nombre');
                        @endphp
                        @foreach($propietariosUnicos as $prop)
                            <option value="{{ $prop->id }}">
                                V-{{ $prop->cedula }} - {{ $prop->nombre }} {{ $prop->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección de Apartamento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Inmueble / Apartamento</label>
                    <select name="apartamento_id" id="select-apartamento" required placeholder="Buscar por Torre o Apto...">
                        <option value="">Seleccione inmueble...</option>
                        @foreach($apartamentos as $apto)
                            <option value="{{ $apto->id }}" {{ old('apartamento_id') == $apto->id ? 'selected' : '' }}>
                                Apto {{ $apto->numero }} (Torre {{ $apto->torre }}) - Alícuota: {{ $apto->tipo->alicuota }}%
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Alícuota (Solo lectura, se actualiza por JS) -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Alícuota del Apartamento (%)</label>
                    <input type="text" id="input-alicuota" value="0.00" disabled
                        style="padding: 0.8rem; border-radius: 8px; border: 1px dashed var(--color-borde); background: var(--color-acentuar-suave); color: var(--color-texto-secundario);">
                    <small style="color: var(--color-texto-secundario);">Valor cargado automáticamente de la base de datos.</small>
                </div>

                <!-- Fecha Vencimiento -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-weight: 500;">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', now()->addDays(15)->format('Y-m-d')) }}" required
                        style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--color-borde); background: var(--color-superficie); color: var(--color-texto);">
                </div>
            </div>

            <!-- Vista Previa de Gastos Aplicados -->
            <div style="margin-bottom: 2rem; background: var(--color-acentuar-suave); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--color-acentuar);">
                <h3 style="color: var(--color-acentuar); margin-bottom: 1rem;">Cálculo Estimado</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Base de Gastos del Mes Seleccionado:</span>
                    <span id="base-gastos" style="font-weight: 600;">$ 0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Participación (Alícuota):</span>
                    <span id="label-alicuota" style="font-weight: 600;">0.00 %</span>
                </div>
                <h4 style="margin-top: 1.5rem; margin-bottom: 0.5rem; color: var(--color-texto);">Desglose Proyectado Especializado</h4>
                <div style="background: #fff; border: 1px solid var(--color-borde); border-radius: 8px; overflow: hidden; margin-bottom: 1rem;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                        <thead>
                            <tr style="background: var(--color-superficie); border-bottom: 1px solid var(--color-borde); text-align: left;">
                                <th style="padding: 0.8rem;">Concepto del Mes</th>
                                <th style="padding: 0.8rem;">Costo Global ($)</th>
                                <th style="padding: 0.8rem;">Fracción Válida ($)</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-desglose">
                            <tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Seleccione un mes y apartamento para poblar la proyección</td></tr>
                        </tbody>
                    </table>
                </div>

                <hr style="border: 0.5px solid var(--color-acentuar); opacity: 0.3; margin: 1rem 0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.2rem; font-weight: bold;">Monto a Recibir en Recibo:</span>
                    <span id="monto-total" style="font-size: 1.8rem; font-weight: bold; color: var(--color-acentuar);">$ 0.00</span>
                </div>
                <small style="display: block; text-align: right; color: var(--color-texto-secundario); margin-top: 0.5rem;">
                    Nota: El servidor realizará el cálculo exacto al guardar y se enviará la notificación por correo con este listado.
                </small>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="button" class="boton boton-primario" onclick="abrirModal(this.closest('form'))">Generar Recibo Individual</button>
                <a href="{{ route('recibos.index') }}" class="boton" style="background: var(--color-borde);">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gastosMesData = @json($gastos);
            const apartamentosData = @json($apartamentos);
            const facturadosPorMesData = @json($facturadosPorMes);
            
            const inputAlicuota = document.getElementById('input-alicuota');
            const baseGastos = document.getElementById('base-gastos');
            const labelAlicuota = document.getElementById('label-alicuota');
            const montoTotal = document.getElementById('monto-total');
            const tablaDesglose = document.getElementById('tabla-desglose');

            // Inicializar Tom Selects
            const tsMesMasivo = new TomSelect("#select-mes-masivo", { create: false });
            const tsTipoMasivo = new TomSelect("#select-tipo-masivo", { create: false });
            const tsMes = new TomSelect("#select-mes", { create: false });
            const tsPropietario = new TomSelect("#select-propietario", { create: false });
            const tsApto = new TomSelect("#select-apartamento", { create: false });

            let aptosDisponiblesParaMes = [...apartamentosData]; // arrancar con todos

            function actualizarFiltrosPorMes(mesId) {
                if (!mesId || !facturadosPorMesData[mesId]) {
                    aptosDisponiblesParaMes = [...apartamentosData];
                } else {
                    const facturados = facturadosPorMesData[mesId];
                    aptosDisponiblesParaMes = apartamentosData.filter(a => !facturados.includes(a.id));
                }

                // Actualizar options Propietario
                const curProp = tsPropietario.getValue();
                tsPropietario.clear();
                tsPropietario.clearOptions();
                
                const propsDisponibles = new Map();
                aptosDisponiblesParaMes.forEach(a => {
                    if (a.propietario) propsDisponibles.set(a.propietario.id, a.propietario);
                });

                propsDisponibles.forEach(prop => {
                    tsPropietario.addOption({
                        value: prop.id,
                        text: `V-${prop.cedula} - ${prop.nombre} ${prop.apellido}`
                    });
                });
                tsPropietario.refreshOptions(false);
                if (propsDisponibles.has(parseInt(curProp))) tsPropietario.setValue(curProp);

                // Llamar al on-change del propietario para repoblar apartamentos
                refrescarAptos(tsPropietario.getValue());
            }

            function refrescarAptos(propId) {
                const currentAptoId = tsApto.getValue();
                
                tsApto.clear();
                tsApto.clearOptions();
                
                let aptosFiltrados = aptosDisponiblesParaMes;
                if (propId) {
                    aptosFiltrados = aptosDisponiblesParaMes.filter(a => a.propietario_id.toString() === propId);
                }
                
                aptosFiltrados.forEach(apto => {
                    tsApto.addOption({
                        value: apto.id,
                        text: `Apto ${apto.numero} (Torre ${apto.torre}) - Alícuota: ${apto.tipo.alicuota}%`
                    });
                });
                tsApto.refreshOptions(false);
                
                if(aptosFiltrados.find(a => a.id.toString() === currentAptoId)) {
                    tsApto.setValue(currentAptoId);
                }
            }

            tsPropietario.on('change', refrescarAptos);

            function recalcular() {
                const mesId = parseInt(tsMes.getValue()) || 0;
                const aptoId = parseInt(tsApto.getValue()) || 0;
                
                let base = 0;
                let alicuota = 0;
                
                if (mesId) {
                    const gastoObj = gastosMesData.find(g => g.id === mesId);
                    if (gastoObj) base = parseFloat(gastoObj.total_gastos) || 0;
                }
                if (aptoId) {
                    const aptoObj = apartamentosData.find(a => a.id === aptoId);
                    if (aptoObj) alicuota = parseFloat(aptoObj.tipo.alicuota) || 0;
                }
                
                inputAlicuota.value = alicuota.toFixed(2);
                baseGastos.textContent = `$ ${base.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                labelAlicuota.textContent = `${alicuota.toFixed(2)} %`;

                let htmlDesglose = '<tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Seleccione un mes y apartamento para poblar la proyección</td></tr>';
                if (mesId && alicuota > 0) {
                    const gastoObj = gastosMesData.find(g => g.id === mesId);
                    if (gastoObj && gastoObj.detalles && gastoObj.detalles.length > 0) {
                        htmlDesglose = '';
                        gastoObj.detalles.forEach(detalle => {
                            const costoProyectado = (parseFloat(detalle.monto) * (alicuota / 100)).toFixed(2);
                            htmlDesglose += `
                                <tr style="border-bottom: 1px solid var(--color-borde);">
                                    <td style="padding: 0.8rem;">${detalle.descripcion}</td>
                                    <td style="padding: 0.8rem;">$ ${parseFloat(detalle.monto).toFixed(2)}</td>
                                    <td style="padding: 0.8rem; color: #2e7d32; font-weight: 600;">$ ${costoProyectado}</td>
                                </tr>
                            `;
                        });
                    } else if (gastoObj) {
                        htmlDesglose = '<tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Este mes no tiene detalles de gasto registrados</td></tr>';
                    }
                }
                tablaDesglose.innerHTML = htmlDesglose;
                
                const total = base * (alicuota / 100);
                montoTotal.textContent = `$ ${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }

            tsMes.on('change', function() {
                recalcular();
                actualizarFiltrosPorMes(tsMes.getValue());
            });
            tsApto.on('change', recalcular);
            
            // Lógica de Previsualización Masiva
                     // Lógica de Previsualización Masiva
            const conteoAptosMasivo = document.getElementById('conteo-aptos-masivo');
            const montoTotalMasivo = document.getElementById('monto-total-masivo');
            const tablaDesgloseMasivo = document.getElementById('tabla-desglose-masivo');
            const tiposData = @json($tipos);

            function recalcularMasivo() {
                const mesId = parseInt(tsMesMasivo.getValue()) || 0;
                const tipoId = parseInt(tsTipoMasivo.getValue()) || 0;
                
                let alicuota = 0;
                let conteoAptos = 0;
                let base = 0;

                if (tipoId) {
                    const tipoObj = tiposData.find(t => t.id === tipoId);
                    if(tipoObj) alicuota = parseFloat(tipoObj.alicuota) || 0;
                    conteoAptos = apartamentosData.filter(a => a.tipo_apartamento_id === tipoId).length;
                }

                let htmlDesglose = '<tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Seleccione mes y tipo para poblar la proyección masiva</td></tr>';
                
                if (mesId && tipoId && alicuota > 0) {
                    const gastoObj = gastosMesData.find(g => g.id === mesId);
                    if (gastoObj && gastoObj.detalles && gastoObj.detalles.length > 0) {
                        htmlDesglose = '';
                        base = parseFloat(gastoObj.total_gastos);
                        gastoObj.detalles.forEach(detalle => {
                            const costoProyectado = (parseFloat(detalle.monto) * (alicuota / 100)).toFixed(2);
                            htmlDesglose += `
                                <tr style="border-bottom: 1px solid var(--color-borde);">
                                    <td style="padding: 0.8rem;">${detalle.descripcion}</td>
                                    <td style="padding: 0.8rem;">$ ${parseFloat(detalle.monto).toFixed(2)}</td>
                                    <td style="padding: 0.8rem; color: #2e7d32; font-weight: 600;">$ ${costoProyectado}</td>
                                </tr>
                            `;
                        });
                    } else if (gastoObj) {
                        htmlDesglose = '<tr><td colspan="3" style="text-align:center; padding: 1rem; color: #777;">Este mes no tiene detalles de gasto</td></tr>';
                    }
                }

                conteoAptosMasivo.textContent = conteoAptos;
                tablaDesgloseMasivo.innerHTML = htmlDesglose;
                
                const total = base * (alicuota / 100);
                montoTotalMasivo.textContent = `$ ${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }

            tsMesMasivo.on('change', recalcularMasivo);
            tsTipoMasivo.on('change', recalcularMasivo);

            // Init inicial
            recalcularMasivo();
            recalcular();

            // Auto-filtra si habia OLD en la iteración inicial
            @if(old('apartamento_id'))
                const rawOldAptoId = "{{ old('apartamento_id') }}";
                const matchingApto = apartamentosData.find(a => a.id.toString() === rawOldAptoId);
                if (matchingApto) {
                    tsPropietario.setValue(matchingApto.propietario_id.toString());
                    tsApto.setValue(rawOldAptoId);
                }
            @endif

            // Lógica Modal
            let formPendiente = null;
            
            window.abrirModal = function(formulario) {
                if (formulario.checkValidity()) {
                    formPendiente = formulario;
                    document.getElementById('modalEmail').showModal();
                } else {
                    formulario.reportValidity();
                }
            }

            window.enviarFormularioPendiente = function() {
                if (formPendiente) {
                    const btnConfirme = document.querySelector('#modalEmail .boton-primario');
                    btnConfirme.textContent = 'Procesando...';
                    btnConfirme.style.pointerEvents = 'none';
                    formPendiente.submit();
                }
            }
        });
    </script>
@endsection
