@props([
    'class' => '',
])

@if ($company->logoUrl())

    <img
        src="{{ $company->logoUrl() }}"
        alt="{{ $company->company_name }}"
        {{ $attributes->merge([
            'class' => $class
        ]) }}
    >

@else

    <div
        {{ $attributes->merge([
            'class' =>
                'flex h-9 w-9 items-center justify-center rounded-lg font-bold text-white '
                .$class
        ]) }}

        style="
            background-color:
            {{ $company->primary_color ?? '#4F46E5' }};
        "
    >
        {{ strtoupper(
            substr(
                $company->company_name ?? 'E',
                0,
                1
            )
        ) }}
    </div>

@endif