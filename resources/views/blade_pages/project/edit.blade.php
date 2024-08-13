@extends('blade_pages.layouts.app')

@section('header', 'Редактировать проект')

@section('content')
<form action='{{ route('projects.update', $id) }}' method='post'>
    @method('PUT')
    @include('blade_pages.project.form')
    <input type="submit" value="Изменить">
</form>
@endsection