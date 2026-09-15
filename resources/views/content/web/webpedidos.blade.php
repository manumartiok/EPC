@extends('layouts.layout-clientes')

@section('title', 'Mis pedidos')

@section('content')
<div class="pt-[10px] pb-[180px] h-full letra max-w-[1366px] mx-auto px-[5%] md:px-[71px]">
    <h3 class="text-[12px] mb-[76px] pt-[20px]">
        <a class="font-bold" href="{{ route('home') }}">Inicio</a> > Mis pedidos
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] border-collapse">
            <thead>
                <tr class="bg-[#FAF8FB] text-[#222222] font-bold text-[14px] h-[52px]">
                    <th class="w-[120px]"></th>
                    <th class="px-3 text-left">N de pedido</th>
                    <th class="px-3 text-left">Fecha de compra</th>
                    <th class="px-3 text-left">Estado</th>
                    <th class="px-3 text-left">Importe</th>
                    <th class="px-3 text-center w-[190px]"></th>
                    <th class="px-3 text-center w-[190px]"></th>
                </tr>
            </thead>
            <tbody class="text-[14px]">
                @forelse($pedidos as $pedido)
                    @php
                        $estadoLabel = [
                            'en_preparacion' => 'EN PREPARACION',
                            'entregado' => 'ENTREGADO',
                            'cancelado' => 'CANCELADO',
                        ][$pedido->estado] ?? strtoupper(str_replace('_', ' ', $pedido->estado));
                    @endphp
                    <tr class="border-b border-[#EEDAF5] h-[98px]">
                        <td class="py-[10px]">
                            <div class="w-[80px] h-[80px] border border-[#EFEFEF] rounded-[3px] flex items-center justify-center text-[#651080]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
  <path d="M25.0837 3.58337V10.75C25.0837 11.7004 25.4612 12.6118 26.1332 13.2838C26.8052 13.9558 27.7166 14.3334 28.667 14.3334H35.8337M17.917 16.125H14.3337M28.667 23.2917H14.3337M28.667 30.4584H14.3337M26.8753 3.58337H10.7503C9.79997 3.58337 8.88853 3.9609 8.21653 4.63291C7.54452 5.30491 7.16699 6.21635 7.16699 7.16671V35.8334C7.16699 36.7837 7.54452 37.6952 8.21653 38.3672C8.88853 39.0392 9.79997 39.4167 10.7503 39.4167H32.2503C33.2007 39.4167 34.1121 39.0392 34.7841 38.3672C35.4561 37.6952 35.8337 36.7837 35.8337 35.8334V12.5417L26.8753 3.58337Z" stroke="#651080" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                            </div>
                        </td>
                        <td class="px-3 text-[#222222]">{{ str_pad($pedido->id, 10, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-3 text-[#222222]">{{ $pedido->created_at?->format('d/m/Y') }}</td>
                        <td class="px-3 text-[#222222] font-bold text-[12px]">{{ $estadoLabel }}</td>
                        <td class="px-3 text-[#222222]">$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
                        <td class="px-3">
                            <a href="{{ route('web-pedidos.pdf', $pedido) }}" target="_blank" rel="noopener"
                                class="flex items-center justify-center w-full h-[38px] border border-[#333333] rounded-[2px] text-[#333333] text-[13px] font-bold uppercase hover:bg-gray-100 transition">
                                Ver detalle
                            </a>
                        </td>
                        <td class="px-3">
                            <form action="{{ route('web-pedidos.recomprar', $pedido) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full h-[38px] bg-[#333333] rounded-[2px] text-white text-[13px] font-bold uppercase hover:bg-[#111111] transition">
                                    Recomprar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-600 bg-white">
                            Todavia no realizaste pedidos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $pedidos->links() }}
    </div>
</div>
@endsection
