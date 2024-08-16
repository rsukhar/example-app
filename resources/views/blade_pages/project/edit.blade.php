@extends('blade_pages.layouts.app')

@section('header', 'Редактировать проект')

@section('content')

<x-form action='{{ route("projects.update", $id) }}' method='post'>
    @method('PUT')
    @csrf
    <x-label name="title">{{ __('Название') }} </x-label>
    <x-input name="title" value="{{ $projectToEdit->title }}" autofocus/>
    <x-label name="assignee_id">{{ __('Ответственный') }}</x-label>
    <x-select :selected="$projectToEdit->assignee_id" :users="$users"/>
    <x-label name="deadline_date">{{ __('Дедлайн') }}</x-label>
    <x-input name="deadline_date" value="{{ $projectToEdit->deadline_date }}" type='date' autofocus/>
    <x-button>
        {{ __('Изменить') }}
    </x-button>
</x-form>
@endsection