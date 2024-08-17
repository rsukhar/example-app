@extends('blade_pages.layouts.app')

@section('header', 'Создать новый проект')

@section('content')

<x-errors/>
<x-form action='{{ route("projects.store") }}' method='post'>
    @csrf
    <x-label name="title">{{ __('Название') }} </x-label>
    <x-input name="title" autofocus/>
    <x-label name="assignee_id">{{ __('Ответственный') }}</x-label>
    <x-select :users="$users"/>
    <x-label name="deadline_date">{{ __('Дедлайн') }}</x-label>
    <x-input name="deadline_date" type='date' autofocus/>
    <x-button>
        {{ __('Создать') }}
    </x-button>
</x-form>
@endsection