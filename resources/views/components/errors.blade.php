@if($errors->any())
    @foreach($errors->all() as $message)
        <x-alert type="warning">
            <x-slot name="heading">
                {{ __('Ошибка #' . $loop->iteration) }}
            </x-slot>
            {{ $message }}
        </x-alert>
    @endforeach
@endif