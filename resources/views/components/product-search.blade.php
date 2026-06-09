@props([
    'action',
    'marcas' => collect(),
])

<form method="GET" action="{{ $action }}" id="filtros-form">
    <div class="w-full bg-[#F2F2F2] rounded-[12px] p-[24px] mb-[40px] flex flex-col gap-[45px]">
        <div class="flex flex-col md:flex-row gap-[28px]">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>

                <input
                    type="text"
                    name="buscar"
                    value="{{ request('buscar') }}"
                    placeholder="Buscar"
                    class="w-full h-[44px] bg-white border border-[#D9D9D9] rounded-[14px] pl-10 pr-4 text-[14px] text-[#3A3A3A] placeholder-[#AAAAAA] focus:outline-none focus:border-[#DE2222]"
                >
            </div>

            <button
                type="submit"
                class="flex justify-center items-center px-[28px] h-[44px] bg-[#DE2222] text-white font-semibold rounded-[14px] text-[15px] hover:bg-[#A60000] transition whitespace-nowrap"
            >
                Buscar
            </button>

            <a
                href="{{ $action }}"
                id="btn-limpiar"
                class="flex justify-center items-center px-[28px] h-[44px] bg-white text-[#DE2222] font-semibold rounded-[14px] text-[15px] border border-[#DE2222] hover:bg-[#DE2222] hover:text-white transition whitespace-nowrap"
            >
                Limpiar
            </a>
        </div>

        <div class="w-full flex flex-col md:flex-row gap-[48px]">
            <div class="flex flex-col gap-[8px] flex-1">
                <h3 class="text-[20px] font-bold ">Marca</h3>

                <div class="relative w-full">
                    <select
                        name="marca"
                        id="select-marca"
                        class="pl-4 w-full h-[44px] appearance-none border border-[#D9D9D9] rounded-[14px] text-[14px] bg-white focus:outline-none focus:border-[#DE2222]"
                    >
                        <option value="">Seleccionar marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" @selected((string) request('marca') === (string) $marca->id)>
                                {{ $marca->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none">
                            <path d="M6.0625 7.5L0.00032264 1.88257e-07L12.1247 -8.71687e-07L6.0625 7.5Z" fill="#747474"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-[8px] flex-1">
                <h3 class="text-[20px] font-bold ">Codigo</h3>

                <input
                    type="text"
                    name="codigo"
                    value="{{ request('codigo') }}"
                    placeholder="Codigo producto"
                    class="w-full h-[44px] border border-[#D9D9D9] rounded-[14px] px-4 text-[14px] bg-white focus:outline-none focus:border-[#DE2222]"
                >
            </div>
        </div>
    </div>
</form>
