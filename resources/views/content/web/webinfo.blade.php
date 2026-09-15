@php
    $info = App\Models\Info::first();
@endphp

@extends('layouts.layout-clientes')

@section('title', 'Info de pagos')

@section('content')

 {{-- contenido  --}}
<div class="pt-[20px] pb-[70px] h-full letra max-w-[1366px] mx-auto px-[5%] md:px-[71px]">
    {{-- bread  --}}
    <div class="text-[12px]  mb-[60px]">
        <h3><a href="{{route('home')}}" class="font-bold">Inicio</a> > Información de pagos</h3>
    </div>
    {{-- contenido  --}}
    <div class="flex flex-col md:flex-row">
        {{-- izquierda  --}}
        <div class="w-full md:w-4/12 mb-[24px]">
            <h1 class="text-[18px] font-bold mb-[24px]">Cuentas bancarias para efectuar el depósito:</h1>
            <h3 class="text-[15px] text-[#5C5C5C]">{!! $info->texto1 !!}</h3>
            <hr class="mt-[80px] mb-[40px]">
            <h1 class="text-[18px] font-bold mb-[24px]">{{$info->condicion}}</h1>
            <h3 class="text-[15px] text-[#5C5C5C]">{!! $info->texto2 !!}</h3>
        </div>  
        {{-- derecha  --}}
        <div class="w-full md:w-8/12 md:pl-[24px]">
            <div class="bg-[#FAF8FB] px-[20px] py-[28px] w-full">
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('info-pago.enviar') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- fila 1 --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                        <div>
                            <label class="block text-[16px] mb-[6px]">Fecha*</label>
                            <input type="date" name="fecha"
                                class="w-full border border-[#DDDDE0] px-[12px] py-[10px] rounded-md bg-transparent" required>
                        </div>

                        <div>
                            <label class="block text-[14px] mb-[6px]">Importe*</label>
                            <div class="relative">
                                <span class="absolute left-[12px] top-1/2 -translate-y-1/2">$</span>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="importe"
                                    class="w-full border border-[#DDDDE0] pl-[26px] pr-[12px] py-[10px] rounded-md bg-transparent"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    {{-- fila 2 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px] my-[24px]">
                        <div>
                            <label class="block text-[16px] mb-[6px]">Banco*</label>
                            <input type="text" name="banco"
                                class="w-full border border-[#DDDDE0] px-[12px] py-[10px] rounded-md bg-transparent" required>
                        </div>

                        <div>
                            <label class="block text-[16px] mb-[6px]">Sucursal*</label>
                            <input type="text" name="sucursal"
                                class="w-full border border-[#DDDDE0] px-[12px] py-[10px] rounded-md bg-transparent" required>
                        </div>

                        <div>
                            <label class="block text-[16px] mb-[6px]">Facturas canceladas</label>
                            <input type="text" name="facturas_canceladas"
                                class="w-full border border-[#DDDDE0] px-[12px] py-[10px] rounded-md bg-transparent">
                        </div>
                    </div>

                    {{-- observaciones --}}
                    <div>
                        <label class="block text-[16px] mb-[6px]">Observaciones / Aclaraciones</label>
                        <textarea name="observaciones" rows="6"
                            class="w-full border border-[#DDDDE0] px-[12px] py-[10px] rounded-md bg-transparent"></textarea>
                    </div>

                   {{-- footer --}}
                    <div class="flex flex-col md:flex-row items-end gap-[40px] mt-[24px]">
                        {{-- archivo --}}
                        <div class="flex-1 w-full">
                            <label class="block text-[16px] mb-[6px]">
                                Adjuntar archivo*
                            </label>
                            <label
                                class="flex items-center justify-between w-full md:max-w-[364px] border border-[#DDDDE0] rounded-md px-3 h-[46px] cursor-pointer bg-transparent">
                                <span id="nombre-archivo" class="text-[#9CA3AF] text-[14px] ">
                                    Seleccionar archivo
                                </span>
                                {{-- icono --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5M5.83333 6.66667L10 2.5L14.1667 6.66667M10 2.5V12.5" stroke="#651080" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                <input type="file" name="archivo" id="archivo-input" class="hidden" required>
                            </label>
                        </div>

                        {{-- texto --}}
                        <div class="flex items-center pb-[12px] w-full md:w-auto">
                            <h3 class="text-[14px] whitespace-nowrap">
                                * Campos obligatorios
                            </h3>
                        </div>

                        {{-- botón --}}
                        <button type="submit"
                            class="bg-[#333333] text-[14px] font-bold text-white w-full md:w-[181px] h-[39px] rounded-[6px]">
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputArchivo = document.getElementById('archivo-input');
    const nombreArchivo = document.getElementById('nombre-archivo');

    inputArchivo.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            nombreArchivo.textContent = this.files[0].name;
            nombreArchivo.classList.remove('text-[#9CA3AF]');
            nombreArchivo.classList.add('text-[#74716A]');
        } else {
            nombreArchivo.textContent = 'Seleccionar archivo';
            nombreArchivo.classList.add('text-[#9CA3AF]');
        }
    });
});
</script>
@endsection

