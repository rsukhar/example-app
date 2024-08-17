<div {{ $attributes->merge([
    'type' => 'success'
]) }}>
    <h3>
        {{ $heading }}
    </h3>
    <p>
        {{ $slot }}
    </p>
</div>