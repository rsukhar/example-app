@extends('blade_pages.layouts.app')

@section('header', 'Создать новый проект')

@section('content')
<form action='{{ route('projects.store') }}' method='post'>
    @include('blade_pages.project.form')
    <input type="submit" value="Создать">
</form>
@endsection