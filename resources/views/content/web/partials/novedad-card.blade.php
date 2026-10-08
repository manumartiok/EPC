{{-- Tarjeta de novedad (Figma: "Novedad blog") --}}
<div class="novedad-card w-full flex flex-col bg-white border border-[#ECEBEB] rounded-[4px]"
     data-titulo="{{ Str::lower($item->titulo) }}"
     data-texto-chico="{{ Str::lower($item->texto_chico) }}"
     data-categoria="{{ Str::lower($item->texto_chico) }}"
     data-mes="{{ $item->created_at->format('n') }}">

    <img src="{{ $item->foto }}" alt="{{ $item->titulo }}"
         class="h-[294px] w-full object-cover rounded-t-[3px]">

    <div class="flex flex-col flex-1 px-[24px] pt-[24px] pb-[20px]">
        <h4 class="text-[12px] leading-[1.5] font-bold uppercase text-[#00B1F6]" style="font-family:'Inter',sans-serif">
            {{ $item->texto_chico }}
        </h4>

        <h2 class="mt-[8px] text-[24px] leading-[1.2] font-bold text-[#17181B]" style="font-family:'Sora',sans-serif">
            {{ $item->titulo }}
        </h2>

        <div class="mt-[16px] text-[16px] leading-[1.5] text-[#17181B] line-clamp-2" style="font-family:'Inter',sans-serif">
            {!! strip_tags($item->texto) !!}
        </div>

        <a href="{{ route('novedades-detalle', ['id' => $item->id]) }}"
           class="mt-auto pt-[16px] text-[16px] leading-[1.5] font-bold text-[#17181B]" style="font-family:'Inter',sans-serif">
            LEER MÁS
        </a>
    </div>
</div>
