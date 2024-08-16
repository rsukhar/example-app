@props(['required' => false])

<input {{ $attributes->merge([
    'type' => 'text',
    'value' => request()->old($attributes->get('name'))
]) }} {{ $required ? 'required' : ''}}>