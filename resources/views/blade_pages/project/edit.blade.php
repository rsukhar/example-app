@extends('blade_pages.layouts.app')

@section('header', 'Редактировать проект')

@section('content')
<x-form action='{{ route('projects.update', $id) }}' method='post'>
    @method('PUT')
    @include('blade_pages.project.form')
    <input type='submit' value='Изменить'>
</x-form>
@endsection