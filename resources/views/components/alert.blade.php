@props(['alert'])

<div {{ $attributes }}>
    <h3>{{ $alert['title'] }}</h3>
    <p>{{ $alert['message'] }}</p>
    <p>{{ $alert['type'] }}</p>
</div>