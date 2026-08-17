<div>

    <style>
        .report-metrics {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 12px;
        }

        .report-main {
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1fr);
            gap: 16px;
        }

        .report-progress {
            height: 7px;
            overflow: hidden;
            border-radius: 9999px;
            background: #e5e7eb;
        }

        .report-progress-bar {
            height: 100%;
            border-radius: 9999px;
            background-color:
                var(--brand-primary);
        }

        @media (max-width: 1100px) {
            .report-metrics {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .report-main {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .report-metrics {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }
    </style>


    {{-- ============================================================
        HEADER
    ============================================================ --}}
    <div
        class="
            mb-4
            flex flex-col
            gap-3
            lg:flex-row
            lg:items-end
            lg:justify-between
        "
    >

        <div>

            <h1 class="text-xl font-bold text-gray-900">
                Reportes
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Resumen operativo y rendimiento del soporte.
            </p>

        </div>


        <div
            class="
                flex
                flex-wrap
                items-end
                gap-2
            "
        >

            {{-- PERÍODO --}}
            <div>

                <label
                    class="
                        mb-1
                        block
                        text-xs
                        font-medium
                        text-gray-500
                    "
                >
                    Período
                </label>

                <select
                    wire:model.live="period"

                    class="
                        brand-focus
                        rounded-md
                        border-gray-300
                        py-2
                        pe-8
                        text-sm
                        shadow-sm
                    "
                >
                    <option value="7">
                        Últimos 7 días
                    </option>

                    <option value="30">
                        Últimos 30 días
                    </option>

                    <option value="90">
                        Últimos 90 días
                    </option>

                    <option value="year">
                        Este año
                    </option>

                    <option value="all">
                        Todo el historial
                    </option>
                </select>

            </div>


            {{-- CATEGORÍA --}}
            <div>

                <label
                    class="
                        mb-1
                        block
                        text-xs
                        font-medium
                        text-gray-500
                    "
                >
                    Categoría
                </label>

                <select
                    wire:model.live="categoryId"

                    class="
                        brand-focus
                        rounded-md
                        border-gray-300
                        py-2
                        pe-8
                        text-sm
                        shadow-sm
                    "
                >
                    <option value="">
                        Todas
                    </option>

                    @foreach ($categories as $category)

                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>

                    @endforeach
                </select>

            </div>


            {{-- TÉCNICO --}}
            <div>

                <label
                    class="
                        mb-1
                        block
                        text-xs
                        font-medium
                        text-gray-500
                    "
                >
                    Técnico
                </label>

                <select
                    wire:model.live="technicianId"

                    class="
                        brand-focus
                        rounded-md
                        border-gray-300
                        py-2
                        pe-8
                        text-sm
                        shadow-sm
                    "
                >
                    <option value="">
                        Todos
                    </option>

                    <option value="unassigned">
                        Sin asignar
                    </option>

                    @foreach ($technicians as $technician)

                        <option value="{{ $technician->id }}">
                            {{ $technician->name }}
                        </option>

                    @endforeach
                </select>

            </div>


            <button
                type="button"
                wire:click="resetFilters"

                class="
                    rounded-md
                    border
                    border-gray-300
                    bg-white
                    px-3 py-2
                    text-sm
                    font-medium
                    text-gray-600
                    shadow-sm
                    transition
                    hover:bg-gray-50
                    hover:text-gray-900
                "
            >
                Limpiar
            </button>

        </div>

    </div>


    {{-- ============================================================
        PERÍODO ACTUAL
    ============================================================ --}}
    <div
        class="
            mb-4
            flex
            items-center
            justify-between
            rounded-lg
            border
            border-gray-200
            bg-white
            px-4 py-2.5
            shadow-sm
        "
    >

        <div>

            <span
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Mostrando
            </span>

            <span
                class="
                    ms-2
                    text-sm
                    font-semibold
                    text-gray-700
                "
            >
                {{ $periodLabel }}
            </span>

        </div>


        <div
            wire:loading
            class="
                text-xs
                font-medium
                text-gray-400
            "
        >
            Actualizando...
        </div>

    </div>


    {{-- ============================================================
        MÉTRICAS
    ============================================================ --}}
    <div class="report-metrics mb-4">

        {{-- CREADOS --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Creados
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-gray-900
                "
            >
                {{ number_format($stats['created']) }}
            </p>

        </div>


        {{-- RESUELTOS --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Resueltos
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-gray-900
                "
            >
                {{ number_format($stats['resolved']) }}
            </p>

        </div>


        {{-- ACTIVOS --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Activos
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-gray-900
                "
            >
                {{ number_format($stats['active']) }}
            </p>

        </div>


        {{-- CRÍTICOS --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Críticos activos
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-red-600
                "
            >
                {{ number_format($stats['critical']) }}
            </p>

        </div>


        {{-- SIN ASIGNAR --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Sin asignar
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-amber-600
                "
            >
                {{ number_format($stats['unassigned']) }}
            </p>

        </div>


        {{-- TIEMPO MEDIO --}}
        <div
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
            "
        >

            <p
                class="
                    text-xs
                    font-medium
                    uppercase
                    tracking-wide
                    text-gray-400
                "
            >
                Resolución media
            </p>

            <p
                class="
                    mt-1
                    text-xl
                    font-bold
                    text-gray-900
                "
            >
                {{ $stats['average_resolution'] }}
            </p>

        </div>

    </div>


    {{-- ============================================================
        DISTRIBUCIONES
    ============================================================ --}}
    <div class="report-main mb-4">

        {{-- ESTADOS --}}
        <section
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                shadow-sm
            "
        >

            <div
                class="
                    border-b
                    border-gray-200
                    px-4 py-3
                "
            >

                <h2
                    class="
                        text-sm
                        font-semibold
                        text-gray-900
                    "
                >
                    Tickets por estado
                </h2>

                <p
                    class="
                        mt-0.5
                        text-xs
                        text-gray-500
                    "
                >
                    Distribución de tickets creados
                    en el período.
                </p>

            </div>


            <div class="space-y-4 p-4">

                @foreach ($statusDistribution as $item)

                    <div>

                        <div
                            class="
                                mb-1.5
                                flex
                                items-center
                                justify-between
                                gap-3
                            "
                        >

                            <span
                                class="
                                    text-sm
                                    font-medium
                                    text-gray-600
                                "
                            >
                                {{ $item['label'] }}
                            </span>

                            <span
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-900
                                "
                            >
                                {{ $item['total'] }}

                                <span
                                    class="
                                        ms-1
                                        text-xs
                                        font-normal
                                        text-gray-400
                                    "
                                >
                                    {{ $item['percentage'] }}%
                                </span>
                            </span>

                        </div>


                        <div class="report-progress">

                            <div
                                class="report-progress-bar"

                                style="
                                    width:
                                    {{ $item['percentage'] }}%;
                                "
                            ></div>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- PRIORIDADES --}}
        <section
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                shadow-sm
            "
        >

            <div
                class="
                    border-b
                    border-gray-200
                    px-4 py-3
                "
            >

                <h2
                    class="
                        text-sm
                        font-semibold
                        text-gray-900
                    "
                >
                    Tickets por prioridad
                </h2>

                <p
                    class="
                        mt-0.5
                        text-xs
                        text-gray-500
                    "
                >
                    Nivel de prioridad de los
                    tickets creados.
                </p>

            </div>


            <div class="space-y-4 p-4">

                @foreach ($priorityDistribution as $item)

                    <div>

                        <div
                            class="
                                mb-1.5
                                flex
                                items-center
                                justify-between
                                gap-3
                            "
                        >

                            <span
                                class="
                                    text-sm
                                    font-medium
                                    text-gray-600
                                "
                            >
                                {{ $item['label'] }}
                            </span>

                            <span
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-900
                                "
                            >
                                {{ $item['total'] }}

                                <span
                                    class="
                                        ms-1
                                        text-xs
                                        font-normal
                                        text-gray-400
                                    "
                                >
                                    {{ $item['percentage'] }}%
                                </span>
                            </span>

                        </div>


                        <div class="report-progress">

                            <div
                                class="report-progress-bar"

                                style="
                                    width:
                                    {{ $item['percentage'] }}%;
                                "
                            ></div>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>

    </div>


    {{-- ============================================================
        CATEGORÍAS + TÉCNICOS
    ============================================================ --}}
    <div class="report-main">

        {{-- CATEGORÍAS --}}
        <section
            class="
                rounded-xl
                border
                border-gray-200
                bg-white
                shadow-sm
            "
        >

            <div
                class="
                    border-b
                    border-gray-200
                    px-4 py-3
                "
            >

                <h2
                    class="
                        text-sm
                        font-semibold
                        text-gray-900
                    "
                >
                    Categorías principales
                </h2>

                <p
                    class="
                        mt-0.5
                        text-xs
                        text-gray-500
                    "
                >
                    Hasta las 8 categorías con
                    más tickets.
                </p>

            </div>


            @if (count($categoryDistribution))

                <div class="space-y-4 p-4">

                    @foreach ($categoryDistribution as $item)

                        <div>

                            <div
                                class="
                                    mb-1.5
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                "
                            >

                                <span
                                    class="
                                        truncate
                                        text-sm
                                        font-medium
                                        text-gray-600
                                    "
                                >
                                    {{ $item['name'] }}
                                </span>

                                <span
                                    class="
                                        shrink-0
                                        text-sm
                                        font-semibold
                                        text-gray-900
                                    "
                                >
                                    {{ $item['total'] }}

                                    <span
                                        class="
                                            ms-1
                                            text-xs
                                            font-normal
                                            text-gray-400
                                        "
                                    >
                                        {{ $item['percentage'] }}%
                                    </span>
                                </span>

                            </div>


                            <div class="report-progress">

                                <div
                                    class="report-progress-bar"

                                    style="
                                        width:
                                        {{ $item['percentage'] }}%;
                                    "
                                ></div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div
                    class="
                        px-4 py-10
                        text-center
                        text-sm
                        text-gray-400
                    "
                >
                    No hay tickets para mostrar.
                </div>

            @endif

        </section>


        {{-- TÉCNICOS --}}
        <section
            class="
                overflow-hidden
                rounded-xl
                border
                border-gray-200
                bg-white
                shadow-sm
            "
        >

            <div
                class="
                    border-b
                    border-gray-200
                    px-4 py-3
                "
            >

                <h2
                    class="
                        text-sm
                        font-semibold
                        text-gray-900
                    "
                >
                    Rendimiento por técnico
                </h2>

                <p
                    class="
                        mt-0.5
                        text-xs
                        text-gray-500
                    "
                >
                    Tickets asignados, resueltos
                    y activos.
                </p>

            </div>


            @if (count($technicianPerformance))

                <div class="overflow-x-auto">

                    <table
                        class="
                            min-w-full
                            divide-y
                            divide-gray-200
                        "
                    >

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    class="
                                        px-4 py-3
                                        text-left
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    Técnico
                                </th>

                                <th
                                    class="
                                        px-4 py-3
                                        text-center
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    Asignados
                                </th>

                                <th
                                    class="
                                        px-4 py-3
                                        text-center
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    Resueltos
                                </th>

                                <th
                                    class="
                                        px-4 py-3
                                        text-center
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    Activos
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            class="
                                divide-y
                                divide-gray-100
                                bg-white
                            "
                        >

                            @foreach ($technicianPerformance as $technician)

                                <tr class="hover:bg-gray-50">

                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-4 py-3
                                        "
                                    >

                                        <span
                                            class="
                                                text-sm
                                                font-medium
                                                text-gray-900
                                            "
                                        >
                                            {{ $technician['name'] }}
                                        </span>

                                    </td>


                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-4 py-3
                                            text-center
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{ $technician['assigned'] }}
                                    </td>


                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-4 py-3
                                            text-center
                                            text-sm
                                            font-semibold
                                            text-gray-900
                                        "
                                    >
                                        {{ $technician['resolved'] }}
                                    </td>


                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-4 py-3
                                            text-center
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{ $technician['active'] }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div
                    class="
                        px-4 py-10
                        text-center
                        text-sm
                        text-gray-400
                    "
                >
                    No hay información de técnicos
                    para los filtros seleccionados.
                </div>

            @endif

        </section>

    </div>

</div>