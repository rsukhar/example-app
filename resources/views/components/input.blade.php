@props(['required' => false, 'value' => ''])

<input {{ $attributes->merge([
    'type' => 'text',
    'value' => request()->old() ? old($attributes->get('name')) : $value
]) }} {{ $required ? 'required' : ''}}>