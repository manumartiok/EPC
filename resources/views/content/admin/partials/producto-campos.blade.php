{{-- Campos compartidos por producto-creador y producto-editor. Requiere $producto. --}}

{{-- Foto principal --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">
        Foto principal (recomendado 496x496) / (2MB máximo)
    </label>

    @if (!empty($producto->foto))
        <img :src="foto.foto || '{{ $producto->foto }}'"
             class="mb-3 w-full max-w-[300px] object-contain rounded border">
    @endif

    <input type="file" name="foto" accept="image/*" @change="subirFoto"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Ficha técnica (PDF) --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Ficha técnica (PDF, 10MB máximo)</label>

    @if (!empty($producto->archivo))
        <div class="mb-2">
            <a href="{{ asset($producto->archivo) }}" target="_blank" class="text-blue-600 underline">Ver archivo</a>
            <span class="mx-1">|</span>
            <a href="{{ route('adm.producto-delete-archivo', $producto->id) }}" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar archivo?')">Eliminar archivo</a>
        </div>
    @endif

    <input type="file" name="archivo" accept="application/pdf"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Orden / nombres --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Orden</label>
    <input type="text" name="orden" value="{{ old('orden', $producto->orden) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Nombre (se muestra en el listado)</label>
    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Nombre completo (título del detalle)</label>
    <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $producto->nombre_completo) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Descripción (texto debajo del título)</label>
    <textarea name="descripcion" class="ckeditor w-full rounded" rows="6">{!! old('descripcion', $producto->descripcion) !!}</textarea>
</div>

<hr class="my-6">

{{-- Ficha técnica (json) --}}
<div class="mb-4">
    <h4 class="text-[16px] font-semibold text-gray-600 mb-1">Descripción técnica</h4>
    <p class="text-sm text-gray-500 mb-2">Cada fila es un dato con su valor. Ej: "Tensión nominal" → "450 / 750 V".</p>
    <div id="ficha-lista" class="flex flex-col gap-3"></div>
    <button type="button" id="ficha-agregar" class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-100">+ Agregar dato</button>
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Aplicación</label>
    <textarea name="aplicacion" class="ckeditor w-full rounded" rows="6">{!! old('aplicacion', $producto->aplicacion) !!}</textarea>
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Certificado</label>
    <textarea name="certificado" class="ckeditor w-full rounded" rows="6">{!! old('certificado', $producto->certificado) !!}</textarea>
</div>

<hr class="my-6">

{{-- Características (json) --}}
<div class="mb-4">
    <h4 class="text-[16px] font-semibold text-gray-600 mb-1">Características</h4>
    <p class="text-sm text-gray-500 mb-2">Ícono (PNG con fondo transparente, 1MB máximo) y título. El ícono se muestra en azul.</p>
    <div id="car-lista" class="flex flex-col gap-3"></div>
    <button type="button" id="car-agregar" class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-100">+ Agregar característica</button>
</div>

{{-- Tamaños --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Tamaños (tabla)</label>
    <textarea name="tamaños" class="ckeditor w-full rounded" rows="8">{!! old('tamaños', $producto->tamaños) !!}</textarea>
</div>

{{-- Colores (json) --}}
<div class="mb-4">
    <h4 class="text-[16px] font-semibold text-gray-600 mb-1">Colores de aislación</h4>
    <p class="text-sm text-gray-500 mb-2">Cada fila es un tipo de producto (ej: "Unipolares") con los colores que tiene disponibles.</p>
    <div id="col-lista" class="flex flex-col gap-3"></div>
    <button type="button" id="col-agregar" class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-100">+ Agregar tipo</button>
</div>

<hr class="my-6">

<div class="mb-4">
    <label class="inline-flex items-center">
        <input type="checkbox" name="destacado" value="1" class="mr-2" {{ ($producto->destacado ?? true) ? 'checked' : '' }}>
        <span class="text-gray-700">Destacado</span>
    </label>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const PALETA = @json(\App\Models\Producto::COLORES);
    const datos = {
        ficha: @json($producto->ficha_tecnica ?? []),
        car:   @json($producto->caracteristicas ?? []),
        col:   @json($producto->colores ?? [])
    };

    const esc = function (s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    };
    const input = 'w-full border border-gray-300 rounded px-4 py-2';
    const quitar = '<button type="button" class="quitar-fila self-start text-sm text-red-600 underline">Quitar</button>';
    const caja = 'fila border border-gray-300 rounded p-3 flex flex-col gap-2';
    const contadores = { ficha: 0, car: 0, col: 0 };

    const filas = {
        ficha: function (i, d) {
            return '<div class="' + caja + '">' +
                '<input type="text" name="ficha_tecnica[' + i + '][nombre]" value="' + esc(d.nombre) + '" placeholder="Dato (ej: Tensión nominal)" class="' + input + '">' +
                '<input type="text" name="ficha_tecnica[' + i + '][valor]" value="' + esc(d.valor) + '" placeholder="Valor (ej: 450 / 750 V)" class="' + input + '">' +
                quitar + '</div>';
        },
        car: function (i, d) {
            return '<div class="' + caja + '">' +
                '<input type="hidden" name="caracteristicas[' + i + '][foto_actual]" value="' + esc(d.foto) + '">' +
                (d.foto ? '<img src="' + esc(d.foto) + '" alt="" class="w-[60px] h-[60px] object-contain bg-gray-200 rounded p-1">' : '') +
                '<input type="text" name="caracteristicas[' + i + '][titulo]" value="' + esc(d.titulo) + '" placeholder="Título (ej: Norma IRAM 247-3)" class="' + input + '">' +
                '<input type="file" name="caracteristicas[' + i + '][foto]" accept="image/*" class="' + input + '">' +
                quitar + '</div>';
        },
        col: function (i, d) {
            const elegidos = d.colores || [];
            const opciones = Object.keys(PALETA).map(function (clave) {
                return '<label class="inline-flex items-center gap-2 mr-4 mb-1">' +
                    '<input type="checkbox" name="colores[' + i + '][colores][]" value="' + clave + '"' + (elegidos.indexOf(clave) !== -1 ? ' checked' : '') + '>' +
                    '<span class="inline-block w-[18px] h-[18px] rounded-full border border-gray-300" style="background:' + esc(PALETA[clave].estilo) + '"></span>' +
                    '<span class="text-sm">' + esc(PALETA[clave].nombre) + '</span></label>';
            }).join('');

            return '<div class="' + caja + '">' +
                '<input type="text" name="colores[' + i + '][tipo]" value="' + esc(d.tipo) + '" placeholder="Tipo de producto (ej: Unipolares)" class="' + input + '">' +
                '<div>' + opciones + '</div>' +
                quitar + '</div>';
        }
    };

    function agregar(tipo, listaId, d) {
        const lista = document.getElementById(listaId);
        lista.insertAdjacentHTML('beforeend', filas[tipo](contadores[tipo]++, d || {}));
    }

    datos.ficha.forEach(function (d) { agregar('ficha', 'ficha-lista', d); });
    datos.car.forEach(function (d) { agregar('car', 'car-lista', d); });
    datos.col.forEach(function (d) { agregar('col', 'col-lista', d); });

    document.getElementById('ficha-agregar').addEventListener('click', function () { agregar('ficha', 'ficha-lista'); });
    document.getElementById('car-agregar').addEventListener('click', function () { agregar('car', 'car-lista'); });
    document.getElementById('col-agregar').addEventListener('click', function () { agregar('col', 'col-lista'); });

    document.addEventListener('click', function (e) {
        const boton = e.target.closest('.quitar-fila');
        if (boton) {
            boton.closest('.fila').remove();
        }
    });
});
</script>
@endpush
