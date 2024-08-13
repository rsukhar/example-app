@extends('blade_pages.layouts.app')

@section('header', 'Создать новый проект')

@section('content')
<x-form action='{{route("projects.store")}}' method='post'>
    @include('blade_pages.project.form')
    <input type='submit' value='Создать'>
</x-form>
@endsection